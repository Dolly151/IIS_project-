<?php // overview.php
    require_once __DIR__ . '/../data/repository.php';
    $repository = new Repository('localhost', 'root', '', 'mydb');
    $courses = $repository->getAll('kurz');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/overview.css">
    <title>WIS - přehled</title>
</head>
<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container d-flex justify-content-center h-100 align-items-center">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
                    <?php foreach ($courses as $course) :?>
                    <div class="col">
                        <p class="short"><?php echo htmlspecialchars($course['zkratka'])?></p>
                        <p><?php echo htmlspecialchars($course['nazev'])?></p>
                        <p class="garant">Garant předmetu: 
                            <?php 
                                $garant = $repository->getByCondition('uzivatel', ['ID' => $course['garant_ID']]);
                                if (!empty($garant)) {
                                    echo htmlspecialchars($garant[0]['jmeno'].' '.$garant[0]['prijmeni']);
                                }
                                else {
                                    echo 'NULL';
                                }
                            ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

</body>
</html>