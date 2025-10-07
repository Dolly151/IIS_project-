<?php
    require_once('../common/common.php');
    $id = $_GET['id'];

    make_header('xxx', 'course_detail')
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container d-flex justify-content-center h-100">
                <h1>Detail predmetu <?php echo $id?></h1>
            </div>
        </main>
    </div>

</body>
</html>
