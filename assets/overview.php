<?php // overview.php ?>

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
                <div class="row row-cols-4">
                    <!--this is just demonstration, courses will be taken from database-->
                    <div class="col">
                        <p class="short">IZP</p>
                        <p>Základy programování</p>
                        <p class="garant">Garant předmetu: Teacher1</p>
                    </div>
                    <div class="col">
                        <p class="short">IDM</p>
                        <p>Diskrétní matematika</p>
                        <p class="garant">Garant předmetu: Teacher1</p>
                    </div>
                    <div class="col">
                        <p class="short">ILG</p>
                        <p>Lineární algebra</p>
                        <p class="garant">Garant předmetu: Teacher3</p>
                    </div>
                    <div class="col">
                        <p class="short">IUS</p>
                        <p>Úvod do SW inženýrství</p>
                        <p class="garant">Garant předmetu: Teacher3</p>
                    </div>
                    <div class="col">
                        <p class="short">IEL</p>
                        <p>Elektronika pro informační technológie</p>
                        <p class="garant">Garant předmetu: Teacher3</p>
                    </div>
                    <div class="col">
                        <p class="short">INC</p>
                        <p>Návrh číslicových systému</p>
                        <p class="garant">Garant předmetu: Teacher2</p>
                    </div>
                    <div class="col">
                        <p class="short">IOS</p>
                        <p>Operační systémy</p>
                        <p class="garant">Garant předmetu: Teacher1</p>
                    </div>
                    <div class="col">
                        <p class="short">ITW</p>
                        <p>Tvorba webových stránek</p>
                        <p class="garant">Garant předmetu: Teacher2</p>
                    </div>
                    <div class="col">
                        <p class="short">IIS</p>
                        <p>Informační systémy</p>
                        <p class="garant">Garant předmetu: Teacher2</p>
                    </div>
                    <div class="col">
                        <p class="short">IDS</p>
                        <p>Databázové systémy</p>
                        <p class="garant">Garant předmetu: Teacher2</p>
                    </div>
                    <div class="col">
                        <p class="short">IPK</p>
                        <p>Počítačové komunikace a sítě</p>
                        <p class="garant">Garant předmetu: Teacher3</p>
                    </div>
                    <div class="col">
                        <p class="short">ISA</p>
                        <p>Síťové aplikace</p>
                        <p class="garant">Garant předmetu: Teacher3</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>