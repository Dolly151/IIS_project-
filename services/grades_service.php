<?php

require_once __DIR__ . '/../data/repository_factory.php';

class GradesService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function isTeacherOfCourse(int $courseId, int $userId): bool
    {
        // tabulka: lektor_uci_v_kurzu, sloupce: uzivatel_ID, kurz_ID
        $rows = $this->repository->getByCondition(
            'lektor_uci_v_kurzu',
            ['ID'],
            ['kurz_ID' => $courseId, 'uzivatel_ID' => $userId]
        );
        return !empty($rows);
    }

    /**
     * Vrátí seznam hodnocení studenta.
     * Každá položka obsahuje: kurz (id, zkratka, nazev), termín (id, název, typ, datum), body.
     */
    public function getStudentGrades(int $userId): array
    {
        // 1) načti všechny záznamy z uzivatel_ma_hodnoceni
        $rows = $this->repository->getByCondition(
            'uzivatel_ma_hodnoceni',
            ['ID', 'termin_ID', 'body', 'datum'],
            ['uzivatel_ID' => $userId]
        );

        if (empty($rows)) {
            return [];
        }

        $out = [];
        foreach ($rows as $r) {
            $terminId = (int) ($r['termin_ID'] ?? 0);
            $body = (int) ($r['body'] ?? 0);

            // 2) získej info o termínu
            $termin = $this->repository->getOneById('Termin', ['ID', 'nazev', 'typ', 'datum', 'kurz_ID'], $terminId);
            if (!$termin)
                continue;

            // 3) získej info o kurzu
            $kurzId = (int) ($termin['kurz_ID'] ?? 0);
            $kurz = $kurzId > 0
                ? $this->repository->getOneById('Kurz', ['ID', 'zkratka', 'nazev'], $kurzId)
                : null;

            $out[] = [
                'body' => $body,
                'zaznam_datum' => $r['datum'] ?? null,
                'termin' => [
                    'id' => (int) $termin['ID'],
                    'nazev' => $termin['nazev'] ?? '',
                    'typ' => $termin['typ'] ?? null,
                    'datum' => $termin['datum'] ?? null,
                ],
                'kurz' => $kurz ? [
                    'id' => (int) $kurz['ID'],
                    'zkratka' => $kurz['zkratka'] ?? '',
                    'nazev' => $kurz['nazev'] ?? '',
                ] : [
                    'id' => $kurzId,
                    'zkratka' => '',
                    'nazev' => '',
                ],
            ];
        }

        return $out;
    }
    // --- LEKTOR: oprávnění pro hodnocení v kurzu ---
    public function canUserGradeCourse(int $courseId, int $userId): bool
    {
        $role = $_SESSION['role'] ?? null;

        // ADMIN / GARANT může vždy
        if (PermissionService::isUserLoggedIn() && (PermissionService::isUserAdmin() || PermissionService::isUserLector())) {
            return true;
        }

        // Lektor přiřazený ke kurzu?
        $rel = $this->repository->getByCondition('lektor_uci_v_kurzu', ['ID'], [
            'uzivatel_ID' => $userId,
            'kurz_ID' => $courseId
        ]);
        return !empty($rel);
    }

    // --- Termíny patřící do kurzu (pro výběr v UI) ---
    public function getCourseTerms(int $courseId): array
    {
        return $this->repository->getByCondition('Termin', ['ID', 'nazev', 'typ', 'datum'], [
            'kurz_ID' => $courseId
        ]);
    }

    // --- Zapsaní studenti v kurzu  ---
    public function listEnrolledStudents(int $courseId): array
    {
        $links = $this->repository->getByCondition('student_navstevuje_kurz', [], ['kurz_ID' => $courseId]);
        $out = [];
        foreach ($links as $l) {
            $uid = (int) $l['uzivatel_ID'];
            $u = $this->repository->getOneById('uzivatel', ['ID', 'jmeno', 'prijmeni', 'email'], $uid);
            if ($u)
                $out[] = $u;
        }
        return $out;
    }

    /** matice všech bodů v kurzu: [uzivatel_ID][termin_ID] => ['body'=>X,'datum'=>Y] */
    public function getGradesMatrix(int $courseId): array
    {
        $terms = $this->getCourseTerms($courseId);
        $matrix = [];
        foreach ($terms as $t) {
            $tid = (int) $t['ID'];
            $rows = $this->repository->getByCondition('uzivatel_ma_hodnoceni', ['uzivatel_ID', 'body', 'datum'], [
                'termin_ID' => $tid
            ]);
            foreach ($rows as $r) {
                $uid = (int) $r['uzivatel_ID'];
                if (!isset($matrix[$uid]))
                    $matrix[$uid] = [];
                $matrix[$uid][$tid] = ['body' => (int) $r['body'], 'datum' => $r['datum'] ?? null];
            }
        }
        return $matrix;
    }

    // --- Body pro jeden termín jako mapa [uzivatel_ID => body] ---
    public function getPointsForTerm(int $terminId): array
    {
        $rows = $this->repository->getByCondition('uzivatel_ma_hodnoceni', ['uzivatel_ID', 'body'], [
            'termin_ID' => $terminId
        ]);
        $map = [];
        foreach ($rows as $r) {
            $map[(int) $r['uzivatel_ID']] = (int) $r['body'];
        }
        return $map;
    }

    // --- Vložit/aktualizovat body studenta v termínu ---
    public function upsertPoints(int $terminId, int $studentId, int $points): bool
    {
        $existing = $this->repository->getByCondition('uzivatel_ma_hodnoceni', ['ID'], [
            'termin_ID' => $terminId,
            'uzivatel_ID' => $studentId
        ]);

        $payload = [
            'termin_ID' => $terminId,
            'uzivatel_ID' => $studentId,
            'body' => $points,
            'datum' => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $id = (int) $existing[0]['ID'];
            return $this->repository->updateId('uzivatel_ma_hodnoceni', $id, $payload);
        } else {
            return $this->repository->insert('uzivatel_ma_hodnoceni', $payload);
        }
    }

}
