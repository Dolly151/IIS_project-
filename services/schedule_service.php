<?php
require_once __DIR__ . '/../data/repository_factory.php';

class ScheduleService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create();
    }

    public function getWeeklyScheduleForStudent(int $studentId, DateTime $monday): array
    {
        // najdi kurzy, kam je student zapsaný
        $rows = $this->repository->getByCondition(
            'student_navstevuje_kurz',
            ['kurz_ID'],
            ['uzivatel_ID' => $studentId]
        );
        if (empty($rows)) {
            return [];
        }

        $out = [];

        foreach ($rows as $r) {
            $cid = (int) ($r['kurz_ID'] ?? 0);
            if ($cid <= 0) {
                continue;
            }

            // dotáhni kurz včetně dne a času
            $c = $this->repository->getOneById(
                'Kurz',
                ['ID', 'nazev', 'zkratka', 'den', 'vyuka_od', 'vyuka_do'],
                $cid
            );
            if (!$c) {
                continue;
            }

            $den = (int) ($c['den'] ?? 0);
            $od  = $c['vyuka_od'] ?? null;

            if ($den < 1 || $den > 5 || !$od) {
                // bez rozvrhu přeskoč
                continue;
            }

            // spočti konkrétní datum pro ten týden
            $dayDate  = (clone $monday)->modify('+' . ($den - 1) . ' days');
            $startsAt = $dayDate->format('Y-m-d') . ' ' . $c['vyuka_od'];
            $endsAt   = !empty($c['vyuka_do'])
                ? $dayDate->format('Y-m-d') . ' ' . $c['vyuka_do']
                : null;

            // blok pro VÝUKU
            $out[] = [
                'course_id'   => (int) $c['ID'],
                'course_name' => $c['nazev'] ?? '',
                'course_code' => $c['zkratka'] ?? '',
                'starts_at'   => $startsAt,
                'ends_at'     => $endsAt,
                'type'        => 'výuka',
                'term_css'    => 'schedule-course',
                'term_id'     => null, // u výuky žádný konkrétní termín
            ];

            // dotáhni termíny daného kurzu (zkoušky, domácí úkoly atd.)
            $term = $this->repository->getByCondition(
                'Termin',
                ['ID', 'nazev', 'typ', 'datum', 'popis'],
                ['kurz_ID' => $cid]
            );

            foreach ($term as $t) {
                $termStart = new DateTime($t['datum']);
                $weekStart = clone $monday;
                $weekEnd   = (clone $monday)->modify('+6 days');

                // zobraz jen termíny, které spadají do daného týdne
                if ($termStart < $weekStart || $termStart > $weekEnd) {
                    continue;
                }

                $out[] = [
                    'course_id'   => $cid,
                    'course_name' => $c['nazev'],
                    'course_code' => $c['zkratka'],
                    'starts_at'   => $termStart->format('Y-m-d H:i:s'),
                    'ends_at'     => null,
                    'type'        => 'termín ' . ($t['nazev'] ?? ''),
                    'description' => $t['popis'] ?? '',
                    'term_css'    => 'schedule-term',
                    'term_id'     => (int) $t['ID'],   // ← důležité pro term_detail.php
                ];
            }
        }

        // seřaď podle začátku
        usort($out, fn($a, $b) => strcmp($a['starts_at'], $b['starts_at']));

        return $out;
    }
}
