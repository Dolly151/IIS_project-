<?php 
require_once('../common/common.php');
require_once('../services/permission_service.php');
require_once('../services/schedule_service.php');

if (!PermissionService::isUserLoggedIn() || !PermissionService::isUserStudent()) {
    header('Location: login.php'); exit;
}

// načti CSS: assets/css/schedule.css
make_header('WIS- rozvrh', 'schedule');

date_default_timezone_set('Europe/Prague');

// ---------- navigace po týdnech ----------
$weekOffset = isset($_GET['week_offset']) ? (int)$_GET['week_offset'] : 0;
$today  = new DateTime('today');
$dow    = (int)$today->format('N'); // 1=Po
$monday = (clone $today)->modify('-' . ($dow - 1) . ' days')->modify(($weekOffset >= 0 ? '+' : '') . $weekOffset . ' week');
$sunday = (clone $monday)->modify('+6 days');

// ---------- data pro grid ----------
$studentId = (int)($_SESSION['user_id'] ?? 0);
$service   = new ScheduleService();
$items     = $service->getWeeklyScheduleForStudent($studentId, $monday);

// parametry mřížky
$startHour = 8;   // první zobrazovaná hodina
$endHour   = 18;  // poslední zobrazovaná hodina (exkluzivně)
$slotMin   = 30;  // granularita mřížky v minutách (musí sedět s CSS výškou slotu)

$czDays = [
    1 => 'Pondělí',
    2 => 'Úterý',
    3 => 'Středa',
    4 => 'Čtvrtek',
    5 => 'Pátek',
];

$totalMinutes = ($endHour - $startHour) * 60;

function minutesFromStart(DateTime $dt, int $startHour): int {
    $h = (int)$dt->format('H');
    $m = (int)$dt->format('i');
    return ($h - $startHour) * 60 + $m;
}

/**
 * Rozdělí události dne do sloupců podle kolizí.
 * Do každé položky doplní 'col' (0..n-1) a 'cols' (počet sloupců v kolizním klastru).
 */
function allocateColumnsForDay(array &$events): void {
    if (empty($events)) return;

    // seřaď podle startu; při shodě delší dřív
    usort($events, function($a, $b) {
        $cmp = $a['start'] <=> $b['start'];
        if ($cmp !== 0) return $cmp;
        $aDur = $a['end']->getTimestamp() - $a['start']->getTimestamp();
        $bDur = $b['end']->getTimestamp() - $b['start']->getTimestamp();
        return $bDur <=> $aDur;
    });

    $active = [];             // [colIndex => eventIndex]
    $clusterId = 0;
    $clusterOf = [];          // eventIndex => clusterId
    $maxColsInCluster = [];   // clusterId  => max used (col+1)

    foreach ($events as $idx => &$ev) {
        // uvolni sloupce, které skončily
        foreach ($active as $col => $aIdx) {
            if ($events[$aIdx]['end'] <= $ev['start']) {
                unset($active[$col]);
            }
        }

        // když není nic aktivního, nový klastr
        if (empty($active)) {
            $clusterId++;
        }

        // nejmenší volný sloupec
        $used = array_keys($active);
        sort($used);
        $col = 0;
        foreach ($used as $u) {
            if ($u === $col) { $col++; } else { break; }
        }

        $ev['col']  = $col;
        $clusterOf[$idx] = $clusterId;
        $active[$col] = $idx;

        $maxColsInCluster[$clusterId] = max($maxColsInCluster[$clusterId] ?? 0, $col+1);
    }
    unset($ev);

    // doplň 'cols'
    foreach ($events as $idx => &$ev) {
        $cid = $clusterOf[$idx];
        $ev['cols'] = $maxColsInCluster[$cid] ?? 1;
    }
    unset($ev);
}

// ---------- seskup události podle dnů a spočti pozice ----------
$byDay = [1=>[],2=>[],3=>[],4=>[],5=>[]];

foreach ($items as $it) {
    $start = new DateTime($it['starts_at']);
    $end   = !empty($it['ends_at']) ? new DateTime($it['ends_at']) : (clone $start)->modify('+90 minutes');

    $dayIdx = (int)$start->format('N'); // 1=Po … 7=Ne
    if ($dayIdx < 1 || $dayIdx > 5) continue; // zobrazujeme Po–Pá

    // ořízni mimo okno (8–18)
    $startClamp = clone $start;
    $endClamp   = clone $end;
    if ((int)$startClamp->format('H') < $startHour) $startClamp = new DateTime($start->format('Y-m-d') . sprintf(' %02d:00:00', $startHour));
    if ((int)$endClamp->format('H') >= $endHour)   $endClamp   = new DateTime($start->format('Y-m-d') . sprintf(' %02d:00:00', $endHour));

    $topMin = max(0, minutesFromStart($startClamp, $startHour));
    $durMin = max(10, minutesFromStart($endClamp, $startHour) - $topMin);

    $byDay[$dayIdx][] = [
        'course_id'   => (int)$it['course_id'],
        'course_code' => $it['course_code'] ?: (string)$it['course_id'],
        'course_name' => $it['course_name'] ?? '',
        'type'        => $it['type'] ?? 'výuka',
        'start'       => $start,
        'end'         => $end,
        'top_pct'     => max(0.0, min(100.0, ($topMin / $totalMinutes) * 100.0)),
        'height_pct'  => max(3.5, ($durMin / $totalMinutes) * 100.0),
    ];
}

// pro každý den rozdělit do sloupců dle kolizí
foreach ([1,2,3,4,5] as $d) {
    allocateColumnsForDay($byDay[$d]);
}

// časové značky (osa)
$timeTicks = [];
$cur = new DateTime(sprintf('%s %02d:00:00', $monday->format('Y-m-d'), $startHour));
$end = new DateTime(sprintf('%s %02d:00:00', $monday->format('Y-m-d'), $endHour));
while ($cur < $end) {
    $timeTicks[] = $cur->format('H:i');
    $cur->modify('+' . $slotMin . ' minutes');
}
?>

<body>
<div class="wrapper d-flex">
    <header><?php include __DIR__ . '/menu.php'; ?></header>

    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a class="btn btn-outline-secondary" href="?week_offset=<?= $weekOffset-1 ?>">&laquo; Minulý týden</a>
            <h2 class="m-0">Týden <?= $monday->format('j.n.Y') ?> – <?= $sunday->format('j.n.Y') ?></h2>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-primary" href="?week_offset=0">Tento týden</a>
                <a class="btn btn-outline-secondary" href="?week_offset=<?= $weekOffset+1 ?>">Příští týden &raquo;</a>
            </div>
        </div>

        <!-- HLAVIČKA MŘÍŽKY -->
        <div class="rozvrh-head d-none d-md-grid">
            <div class="time-col-head"></div>
            <?php foreach ([1,2,3,4,5] as $d): ?>
                <div class="day-col-head"><?= htmlspecialchars($czDays[$d]) ?></div>
            <?php endforeach; ?>
        </div>

        <div class="rozvrh-grid">
            <!-- levá časová osa -->
            <div class="time-col">
                <?php foreach ($timeTicks as $t): ?>
                    <div class="time-cell"><span><?= htmlspecialchars($t) ?></span></div>
                <?php endforeach; ?>
            </div>

            <!-- sloupce dní Po–Pá -->
            <?php foreach ([1,2,3,4,5] as $d): ?>
                <div class="day-col">
                    <!-- horizontální linky -->
                    <?php foreach ($timeTicks as $_): ?>
                        <div class="day-slot"></div>
                    <?php endforeach; ?>

                    <!-- bloky událostí -->
                    <?php foreach ($byDay[$d] as $blk): ?>
                        <?php
                            // šířka a offset v % v rámci jednoho dne
                            $wPct = 100 / max(1, (int)$blk['cols']);
                            $lPct = $wPct * (int)$blk['col'];
                            // mezery 6px zleva/6px zprava (souhlasí s CSS paddingem sloupce)
                            $inlineStyle = sprintf(
                                'top: %.4f%%; height: %.4f%%; left: calc(%.4f%% + 6px); width: calc(%.4f%% - 12px);',
                                $blk['top_pct'],
                                $blk['height_pct'],
                                $lPct,
                                $wPct
                            );
                        ?>
                        <a href="course_detail.php?id=<?= (int)$blk['course_id'] ?>"
                           class="event-block"
                           style="<?= $inlineStyle ?>">
                            <div class="code"><?= htmlspecialchars($blk['course_code']) ?></div>
                            <div class="name text-truncate"><?= htmlspecialchars($blk['course_name']) ?></div>
                            <div class="meta"><?= $blk['start']->format('H:i') ?>–<?= $blk['end']->format('H:i') ?> · <?= htmlspecialchars($blk['type']) ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($items)): ?>
            <div class="alert alert-info mt-3">Pro tento týden zatím nemáš v rozvrhu nic.</div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
