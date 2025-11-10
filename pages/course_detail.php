<?php
    require_once('../common/common.php');
    require_once('../services/detail_service.php');
    require_once('../services/my_courses_service.php');
    require_once('../services/permission_service.php');
    require_once('../services/grades_service.php');

    // bezpečně převezmeme id kurzu
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        redirect('overview.php?error=' . urlencode('Chybné ID kurzu'));
        exit;
    }

    $service          = new DetailService();
    $myCoursesService = new MyCoursesService();
    $gradesService    = new GradesService();

    $course = $service->getCourseDetail($id);

    // když se kurz nenašel
    if (!$course) {
        make_header('Detail předmětu', 'course_detail');
        echo '<body><div class="wrapper d-flex"><header>';
        include __DIR__ . '/menu.php';
        echo '</header><main class="container py-5"><div class="alert alert-danger">Kurz nebyl nalezen.</div></main></div></body></html>';
        exit;
    }

    make_header('Detail předmětu', 'course_detail');
?>

<body>
    <div class="wrapper d-flex">
        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container d-flex flex-column py-5">
                <h1>Detail předmětu <?php echo "'" . htmlspecialchars($course['nazev']) . "'"?></h1>
                <hr>

                <div class="container h-50 detail">
                    <div class="row">
                        <div class="col-sm-3"><p>Název</p></div>
                        <div class="col-sm-9"><p><?php echo htmlspecialchars($course['nazev']); ?></p></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3"><p>Garant</p></div>
                        <div class="col-sm-9">
                            <p><?php echo htmlspecialchars($course['garant']['jmeno'] . ' ' . $course['garant']['prijmeni']); ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3"><p>Popis</p></div>
                        <div class="col-sm-9"><p><?php echo htmlspecialchars($course['popis']); ?></p></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3"><p>Cena</p></div>
                        <div class="col-sm-9"><p><?php echo htmlspecialchars($course['cena']); ?></p></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3"><p>Limit</p></div>
                        <div class="col-sm-9"><p><?php echo htmlspecialchars($course['limit']); ?></p></div>
                    </div>

                    <?php
                    // volitelné: pokud má kurz vyplněný rozvrhový slot, ukážeme i ten
                    $dny = [1=>'Pondělí',2=>'Úterý',3=>'Středa',4=>'Čtvrtek',5=>'Pátek'];
                    if (!empty($course['den']) && !empty($course['vyuka_od']) && !empty($course['vyuka_do'])): ?>
                        <div class="row">
                            <div class="col-sm-3"><p>Výuka</p></div>
                            <div class="col-sm-9">
                                <p>
                                    <?php
                                        $den = (int)$course['den'];
                                        echo htmlspecialchars(($dny[$den] ?? (string)$den) . ' ' . substr($course['vyuka_od'],0,5) . '–' . substr($course['vyuka_do'],0,5));
                                    ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Akční tlačítka -->
                <div class="container my-4 d-flex gap-2 flex-wrap">

                    <?php
                    // Tlačítko pro přidání do kurzu (jen pro STUDENTA, který ještě není zapsán)
                    if (PermissionService::isUserLoggedIn() && PermissionService::isUserStudent()) {
                        $studentId = (int)($_SESSION['user_id'] ?? 0);
                        $isStudentInCourse = $myCoursesService->isStudentInCourse($studentId, $id);

                        if (!$isStudentInCourse) { ?>
                            <a href="actions/course_register_action.php?id=<?= urlencode((string)$id); ?>" class="btn btn-primary">
                                Zapsat se do kurzu
                            </a>
                    <?php } } ?>

                    <?php
                    // Tlačítko „Spravovat lektory“ (jen pro GARANTA TOHOTO kurzu)
                    if (PermissionService::isUserLoggedIn() && PermissionService::isUserGarant()) {
                        $currentUserId = (int)($_SESSION['user_id'] ?? 0);
                        if (!empty($course['garant_ID']) && (int)$course['garant_ID'] === $currentUserId) { ?>
                            <a class="btn btn-outline-secondary" href="course_teachers.php?id=<?= (int)$id ?>">
                                Spravovat lektory
                            </a>
                    <?php } } ?>

                    <?php
                    // Tlačítko „Hodnocení“ – ZOBRAZIT JEN, pokud jsme sem přišli z Vyučovaných kurzů (ctx=taught)
                    // a současně má uživatel oprávnění (admin || garant || lektor přiřazený ke kurzu).
                    $showGradesBtn = false;
                    if (isset($_GET['ctx']) && $_GET['ctx'] === 'taught' && PermissionService::isUserLoggedIn()) {
                        $uid = (int)($_SESSION['user_id'] ?? 0);
                        if (PermissionService::isUserAdmin() || PermissionService::isUserGarant()) {
                            $showGradesBtn = true;
                        } else {
                            // lektor přiřazený ke kurzu
                            $showGradesBtn = $gradesService->isTeacherOfCourse((int)$id, $uid);
                        }
                    }

                    if ($showGradesBtn) { ?>
                        <a class="btn btn-outline-primary" href="gradebook.php?id=<?= (int)$id ?>">
                            Hodnocení
                        </a>
                    <?php } ?>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
