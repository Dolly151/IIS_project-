<?php
require_once('../common/common.php');
require_once('../services/permission_service.php');
require_once('../services/vyucovane_kurzy_service.php');

if (!PermissionService::isUserLoggedIn()) {
    redirect('login.php');
    exit;
}

$userId = (int) ($_SESSION['user_id'] ?? 0);
$svc = new TaughtCoursesService();
$courses = $svc->listByTeacher($userId);

make_header('WIS – Vyučované kurzy', 'overview'); // klidně změň CSS
?>

<body>
    <div class="wrapper d-flex">
        <header><?php include __DIR__ . '/menu.php'; ?></header>

        <main>
            <div class="container d-flex flex-column py-5">
                <h1>Vyučované kurzy</h1>
                <hr>
                <?php if (empty($courses)): ?>
                    <div class="alert alert-info">Nemáš přiřazené žádné vyučované kurzy.</div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 gap-3">
                        <?php foreach ($courses as $c): ?>
                            <div class="col d-flex flex-column gap-3 w-auto h-auto p-3">
                                <div>
                                    <h3 class="short"><?= htmlspecialchars($c['zkratka']) ?></h3>
                                    <p><?= htmlspecialchars($c['nazev']) ?></p>
                                </div>
                                <div>
                                    <?php if (!empty($c['garant'])): ?>
                                        <p class="garant mb-2">Garant předmětu: <?= htmlspecialchars($c['garant']) ?></p>
                                    <?php endif; ?>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-sm btn-primary"
                                            href="course_detail.php?id=<?= (int) $c['ID'] ?>&ctx=taught">Detail</a>
                                        <a class="btn btn-sm btn-primary" href="gradebook.php?id=<?= (int) $c['ID'] ?>">Seznam
                                            studentů</a>
                                        <a class="btn btn-sm btn-primary" href="term_create.php?id=<?= (int) $c['ID'] ?>">Vypsat
                                            termín</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>