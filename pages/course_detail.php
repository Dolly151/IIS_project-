<?php
    require_once('../common/common.php');
    require_once('../services/detail_service.php');

    $id = $_GET['id'];

    $service = new DetailService();
    $course = $service->getCourseDetail($id);

    make_header('xxx', 'course_detail')
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <h1 class="text-center">Detail předmětu <?php echo $id?></h1>
            <div class="container p-3 h-50 detail">
                <div class="row">
                    <div class="col-2">
                        <p>Název</p>
                    </div>
                    <div class="col-10">
                        <p><?php echo htmlspecialchars($course['nazev'])?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <p>Garant</p>
                    </div>
                    <div class="col-10">
                        <p><?php echo htmlspecialchars($course['garant']['jmeno']. ' '. $course['garant']['prijmeni'])?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <p>Popis</p>
                    </div>
                    <div class="col-10">
                        <p><?php echo htmlspecialchars($course['popis'])?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <p>Cena</p>
                    </div>
                    <div class="col-10">
                        <p><?php echo htmlspecialchars($course['cena'])?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <p>Limit</p>
                    </div>
                    <div class="col-10">
                        <p>xxx</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
