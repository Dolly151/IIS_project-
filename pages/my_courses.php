<?php
require_once('../common/common.php');
require_once('../services/permission_service.php');
require_once('../services/my_courses_service.php');

if (!PermissionService::isUserLoggedIn() || !PermissionService::isUserStudent()) {
    header('Location: login.php'); exit;
}

make_header('WIS - Moje kurzy', 'my_courses');

$studentId = (int)($_SESSION['user_id'] ?? 0);
$service   = new MyCoursesService();
$courses   = $service->getStudentCourses($studentId);
?>

<body>
<div class="wrapper d-flex">
    <header>
        <?php include __DIR__ . '/menu.php'; ?>
    </header>

    <main class="container py-4">
        <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <h1 class="mb-3">Moje kurzy</h1>

        <?php if (empty($courses)): ?>
            <div class="alert alert-info">Zatím nejsi zapsán v žádném kurzu.</div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach ($courses as $c): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1">
                                    <?= htmlspecialchars($c['nazev'], ENT_QUOTES, 'UTF-8') ?>
                                </h5>
                                <small class="text-muted">
                                    Garant:
                                    <?php
                                    $g = $c['garant'];
                                    $gName = $g ? (($g['jmeno'] ?? '') . ' ' . ($g['prijmeni'] ?? '')) : '—';
                                    echo htmlspecialchars(trim($gName), ENT_QUOTES, 'UTF-8');
                                    ?>
                                </small>
                                <?php if (!empty($c['popis'])): ?>
                                    <p class="mt-2"><?= htmlspecialchars($c['popis'], ENT_QUOTES, 'UTF-8') ?></p>
                                <?php endif; ?>

                                <div class="mt-auto d-flex gap-2 flex-wrap">
                                    <a href="course_detail.php?id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-info-circle"></i> Detail
                                    </a>
                                    <!-- připravené pro budoucí Rozvrh/Hodnocení -->
                                    <a href="course_grades.php?course_id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-success">
                                        <i class="fa fa-star"></i> Moje hodnocení
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
