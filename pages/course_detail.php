<?php
    require_once('../common/common.php');
    require_once('../services/detail_service.php');
    require_once('../services/my_courses_service.php');

    $id = $_GET['id'];

    $service = new DetailService();
    $myCoursesService = new MyCoursesService();
    $course = $service->getCourseDetail($id);

    make_header('Detail předmětu', 'course_detail')
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container d-flex flex-column py-5">
                <h1>Detail předmětu <?php echo "'". $course['nazev'] . "'"?></h1>
                <hr>
                <div class="container h-50 detail">
                    <div class="row">
                        <div class="col-sm-3">
                            <p>Název</p>
                        </div>
                        <div class="col-sm-9">
                            <p><?php echo htmlspecialchars($course['nazev'])?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <p>Garant</p>
                        </div>
                        <div class="col-sm-9">
                            <p><?php echo htmlspecialchars($course['garant']['jmeno']. ' '. $course['garant']['prijmeni'])?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <p>Popis</p>
                        </div>
                        <div class="col-sm-9">
                            <p><?php echo htmlspecialchars($course['popis'])?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <p>Cena</p>
                        </div>
                        <div class="col-sm-9">
                            <p><?php echo htmlspecialchars($course['cena'])?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <p>Limit</p>
                        </div>
                        <div class="col-sm-9">
                            <p><?php echo htmlspecialchars($course['limit'])?></p>
                        </div>
                    </div>
                </div>
<<<<<<< HEAD
                <?php if (PermissionService::isUserLoggedIn() && !$service->isUserAllreadyRegisteredToCourse($id, $_SESSION['user_id'])) { ?>
                <div class="container text-center">
                    <a href="actions/course_register_action.php?id=<?php echo urlencode($id); ?>" class="btn btn-primary">Zapsat se do kurzu</a>
                </div>
            </div>
            <?php } ?>
=======
                 <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserStudent()) { 
                $studentId = $_SESSION['user_id']; 
                $isStudentInCourse = $myCoursesService->isStudentInCourse($studentId, $id);

                if (!$isStudentInCourse) { ?>
                    <div class="container text-center my-5">
                        <a href="actions/course_register_action.php?id=<?= urlencode($id); ?>" class="btn btn-primary">
                            Zapsat se do kurzu
                        </a>
                    </div>
                <?php } 
            } ?>
>>>>>>> a370625 (feat: add admin rooms managment; add action to add, edit and delete room; update styles; update registration redirect for admin)
        </main>
    </div>

</body>
</html>
