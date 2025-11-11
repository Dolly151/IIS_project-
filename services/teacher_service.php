<?php
require_once __DIR__ . '/../data/repository_factory.php';
require_once __DIR__ . '/permission_service.php';
require_once __DIR__ . '/../common/enums.php';

class TeacherService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    /** Jen garant daného kurzu (ne admin) může spravovat lektory. */
    private function assertGarantOnly(int $courseId): void
    {
        PermissionService::requireRole(PermissionLevel::GARANT);

        $userId = (int)($_SESSION['user_id'] ?? 0);
        $course = $this->repository->getOneById('Kurz', ['garant_ID'], $courseId);
        if (!$course) { http_response_code(404); exit('Kurz nenalezen'); }

        if ((int)$course['garant_ID'] !== $userId) {
            http_response_code(403);
            exit('Nemáš oprávnění spravovat lektory tohoto kurzu.');
        }
    }

    /** Seznam lektorů přiřazených ke kurzu. */
    public function listTeachers(int $courseId): array
    {
        $pairs = $this->repository->getByCondition('lektor_uci_v_kurzu', ['uzivatel_ID'], ['kurz_ID' => $courseId]);
        $out = [];
        foreach ($pairs as $p) {
            $u = $this->repository->getOneById('Uzivatel', ['ID','jmeno','prijmeni','login','role'], (int)$p['uzivatel_ID']);
            if ($u) { $out[] = $u; }
        }
        return $out;
    }

    /** Přidá uživatele jako lektora POUZE pokud už má roli LECTOR. */
    public function addTeacher(int $courseId, int $userId): bool
    {
        $this->assertGarantOnly($courseId);

        $u = $this->repository->getOneById('Uzivatel', ['role'], $userId);
        if (!$u) return false;

        // musí být přesně/lebo aspoň role LECTOR (přísněji == LECTOR)
        if ((int)$u['role'] !== PermissionLevel::LECTOR->value) {
            return false; // nepřidávej, není lektor
        }

        return $this->repository->insert('lektor_uci_v_kurzu', [
            'uzivatel_ID' => $userId,
            'kurz_ID'     => $courseId
        ]);
    }

    /** Odebere lektora z kurzu. */
    public function removeTeacher(int $courseId, int $userId): bool
    {
        $this->assertGarantOnly($courseId);
        $ret = $this->repository->getByCondition('lektor_uci_v_kurzu', [], [
            'uzivatel_ID' => $userId,
            'kurz_ID'     => $courseId
        ]);
        return $this->repository->deleteById('lektor_uci_v_kurzu', $ret[0]['ID']);
    }

    /** Vrátí kandidáty do selectu – pouze uživatele s rolí LECTOR. */
    public function listLecturerCandidates(): array
    {
        return $this->repository->getByCondition(
            'Uzivatel',
            ['ID','jmeno','prijmeni','login','role'],
            ['role' => PermissionLevel::LECTOR->value]
        );
    }
}
