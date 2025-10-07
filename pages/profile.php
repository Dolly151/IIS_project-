<?php
    require_once('../common/common.php');

    make_header('WIS- profile', 'profile');
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container profile d-flex flex-column h-50">
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