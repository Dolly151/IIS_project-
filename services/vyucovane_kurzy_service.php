<?php
require_once __DIR__ . '/../data/repository_factory.php';

class TaughtCoursesService
{
    private Repository $repo;

    public function __construct()
    {
        $this->repo = RepositoryFactory::create();
    }

    /** Všechny kurzy, které učí daný uživatel (lektor) podle tabulky lektor_uci_v_kurzu */
    public function listByTeacher(int $teacherId): array
    {
        $links = $this->repo->getByCondition('lektor_uci_v_kurzu', [], ['uzivatel_ID' => $teacherId]);
        $out = [];
        foreach ($links as $row) {
            $kurzId = (int)$row['kurz_ID'];
            $k = $this->repo->getOneById('Kurz', ['ID','zkratka','nazev','garant_ID'], $kurzId);
            if (!$k) continue;

            // načti garanta (hezké zobrazení)
            $g = null;
            if (!empty($k['garant_ID'])) {
                $g = $this->repo->getOneById('uzivatel', ['ID','jmeno','prijmeni'], (int)$k['garant_ID']);
            }

            $out[] = [
                'ID'      => (int)$k['ID'],
                'zkratka' => $k['zkratka'] ?? '',
                'nazev'   => $k['nazev'] ?? '',
                'garant'  => $g ? ($g['jmeno'].' '.$g['prijmeni']) : '',
            ];
        }
        return $out;
    }
}
