<?php
    require_once '../services/overview_service.php';
    require_once('../common/common.php');
    require_once('../services/permission_service.php');

    $service = new OverviewService();
    $courses = $service->getAllCoursesJoinGarant();

    make_header('WIS- přehled', 'overview');
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container d-flex flex-column py-5 gap-3">
                <?php if (PermissionService::isUserLoggedIn() && (PermissionService::isUserAdmin() || PermissionService::isUserGarant())) { ?>
                    <div class="container p-0">
                        <a href="course_create.php" class="btn btn-primary">+ Vytvořit kurz</a>
                    </div>
                <?php } ?>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 gap-3">
                    <?php foreach ($courses as $course) :?>
                    <div class="col d-flex flex-column gap-4 w-auto h-auto justify-content-center p-3">
                        <div>
                            <h3 class="short"><?php echo htmlspecialchars($course['zkratka'])?></h3>
                            <p><?php echo htmlspecialchars($course['nazev'])?></p>
                        </div>
                        <div>
                            <p class="garant">
                                Garant předmetu: <?php echo htmlspecialchars($course['garant_ID']['jmeno'].' '.$course['garant_ID']['prijmeni']);?>
                            </p>
                            <?php if (isset($course['ID'])) { ?>
                                <a href="course_detail.php?id=<?php echo urlencode($course['ID']); ?>">Zobrazit detail</a>
                            <?php } ?>
                        </div>
                        
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

</body>
</html>