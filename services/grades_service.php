<?php

require_once __DIR__ . '/../data/repository_factory.php';

class GradesService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
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
            $terminId = (int)($r['termin_ID'] ?? 0);
            $body     = (int)($r['body'] ?? 0);

            // 2) získej info o termínu
            $termin = $this->repository->getOneById('Termin', ['ID','nazev','typ','datum','kurz_ID'], $terminId);
            if (!$termin) continue;

            // 3) získej info o kurzu
            $kurzId = (int)($termin['kurz_ID'] ?? 0);
            $kurz = $kurzId > 0
                ? $this->repository->getOneById('Kurz', ['ID','zkratka','nazev'], $kurzId)
                : null;

            $out[] = [
                'body'        => $body,
                'zaznam_datum'=> $r['datum'] ?? null,
                'termin' => [
                    'id'    => (int)$termin['ID'],
                    'nazev' => $termin['nazev'] ?? '',
                    'typ'   => $termin['typ'] ?? null,
                    'datum' => $termin['datum'] ?? null,
                ],
                'kurz' => $kurz ? [
                    'id'      => (int)$kurz['ID'],
                    'zkratka' => $kurz['zkratka'] ?? '',
                    'nazev'   => $kurz['nazev'] ?? '',
                ] : [
                    'id'      => $kurzId,
                    'zkratka' => '',
                    'nazev'   => '',
                ],
            ];
        }

        return $out;
    }
}
