<?php // profile.php ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
    <title>WIS - Profil</title>
</head>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container profile d-flex h-50 profile">
                <h1>Profil uživatele</h1>
                <!--Profile details will be taken from database-->
                <div class="container details">
                    <p>Meno</p>
                    <p>Login</p>
                    <p>Email</p>
                    <p>Rodné číslo</p>
                    <p>Dátum narození</p>
                    <p>Adresa</p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>