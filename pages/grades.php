<?php
require_once('../common/common.php');
require_once('../services/permission_service.php');
require_once('../services/grades_service.php');

PermissionService::requireRole(PermissionLevel::ANY); // stačí být přihlášen
$studentId = (int)($_SESSION['user_id'] ?? 0);

$svc = new GradesService();
$items = $studentId > 0 ? $svc->getStudentGrades($studentId) : [];

$courseId = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
if ($courseId > 0) {
    $items = array_values(array_filter($items, function($r) use ($courseId) {
        return isset($r['kurz']['id']) && (int)$r['kurz']['id'] === $courseId;
    }));
}

make_header('WIS – Moje hodnocení', 'main'); // klidně vytvoř i vlastní CSS soubor, teď stačí main.css

// seskupení a součty podle kurzu
$byCourse = [];
$totals   = [];
foreach ($items as $row) {
    $cid = (int)($row['kurz']['id'] ?? 0);
    if (!isset($byCourse[$cid])) $byCourse[$cid] = [];
    $byCourse[$cid][] = $row;

    if (!isset($totals[$cid])) $totals[$cid] = 0;
    $totals[$cid] += (int)$row['body'];
}

// hezké mapování typu termínu (volitelné)
function termTypeLabel($typ): string {
    $map = [
        0 => 'Přednáška', 1 => 'Cvičení', 2 => 'Zkouška', 3 => 'Domácí úkol',
        4 => 'Projekt',   5 => 'Seminář'
    ];
    return $map[$typ] ?? (string)$typ;
}
?>

<body>
<div class="wrapper d-flex">
    <header><?php include __DIR__ . '/menu.php'; ?></header>

    <main>
        <div class="container py-5">
            <h1 class="mb-3">
                <?php if ($courseId > 0 && !empty($byCourse)): // ak ideme z moje kurzy -> moje hodnotenie, tak vypise len hodnotenie pre dany kurz 
                    $first = reset($byCourse);                 // ak ideme z moje hodnotenie, vypise vsetky hodnotenia 
                    $first = $first[0] ?? null;
                    $code = $first ? htmlspecialchars($first['kurz']['zkratka'] ?: ('KURZ '.$courseId)) : ('Kurz '.$courseId);
                    $name = $first ? htmlspecialchars($first['kurz']['nazev'] ?? '') : '';
                    echo 'Moje hodnocení pro kurz: ' . $code . ($name ? ' – '.$name : '');
                else: ?>
                    Moje hodnocení
                <?php endif; ?>
            </h1>
            <hr>
            <?php if (empty($items)): ?>
                <div class="alert alert-info">Zatím nemáš žádné záznamy hodnocení.</div>
            <?php else: ?>
                <?php foreach ($byCourse as $cid => $rows): 
                    $first = $rows[0];
                    $code  = htmlspecialchars($first['kurz']['zkratka'] ?: ('KURZ '.$cid));
                    $name  = htmlspecialchars($first['kurz']['nazev'] ?: '');
                    $sum   = (int)$totals[$cid];
                ?>
                <section class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <h4 class="m-0">
                            <?= $code ?><?= $name ? ' – '.$name : '' ?>
                        </h4>
                        <div>
                            <span class="badge py-2">Součet: <?= $sum ?> / 100</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Termín</th>
                                    <th style="width: 20%;">Typ</th>
                                    <th style="width: 20%;">Datum</th>
                                    <th style="width: 15%;">Body</th>
                                    <th style="width: 15%;">Zapsáno</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rows as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['termin']['nazev']) ?></td>
                                    <td><?= htmlspecialchars(termTypeLabel($r['termin']['typ'])) ?></td>
                                    <td><?= htmlspecialchars($r['termin']['datum'] ? date('d.m.Y H:i', strtotime($r['termin']['datum'])) : '') ?></td>
                                    <td><strong><?= (int)$r['body'] ?></strong></td>
                                    <td><?= htmlspecialchars($r['zaznam_datum'] ? date('d.m.Y H:i', strtotime($r['zaznam_datum'])) : '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
