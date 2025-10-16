<?php
require_once __DIR__ . '/../services/permission_service.php';

?>

<nav class="navbar">
    <div class="container-fluid d-flex justify-content-center p-0 gap-3">
        <a href="overview.php"><img src="../assets/img/logo.png" alt="FIT VUT logo" class="logo align-self-center"></a>
        <ul class="navbar-nav">
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'overview.php') ? 'active' : ''; ?>">
                <a href="overview.php">
                    <i class="fa fa-list"></i>
                    Přehled
                </a>
            </li>

            <?php if (PermissionService::isUserLoggedIn()) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'profile.php')
                    echo 'active' ?>">
                        <a href="profile.php">
                            <i class="fa fa-user"></i>
                            Profil
                        </a>
                    </li>
            <?php } ?>

            <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserStudent()) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'schedule.php')
                    echo 'active' ?>">
                        <a href="schedule.php">
                            <i class="fa fa-television"></i>
                            Rozvrh
                        </a>
                    </li>
            <?php } ?>

            <?php if (!PermissionService::isUserLoggedIn()) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'login.php')
                    echo 'active' ?>">
                        <a href="login.php">
                            <i class="fa fa-sign-in"></i>
                            Přihlásení
                        </a>
                    </li>
            <?php } ?>
            <?php if (PermissionService::isUserLoggedIn()) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'requests.php')
                    echo 'active'; ?>">
                    <a href="requests.php">
                        <i class="fa fa-sign-in"></i>
                        Žádosti
                    </a>
                </li>
            <?php } ?>

            <!-- ADMIN can see and edit users-->
            <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserAdmin()) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'users.php')
                    echo 'active' ?>">
                        <a href="users.php">
                            <i class="fa fa-users"></i>
                            Uživatele
                        </a>
                    </li>
            <?php } ?>

            <!-- ADMIN can see an edit rooms-->
            <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserAdmin()) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'rooms.php')
                    echo 'active' ?>">
                        <a href="rooms.php">
                            <i class="fa fa-university"></i>
                            Místnosti
                        </a>
                    </li>
            <?php } ?>

            <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserStudent()) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'my_courses.php')
                    echo 'active' ?>">
                        <a href="my_courses.php"><i class="fa fa-book"></i> Moje kurzy</a>
                    </li>
            <?php } ?>

            <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserStudent()) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'grades.php')
                    echo 'active' ?>">
                        <a href="grades.php">
                            <i class="fa fa-star"></i>
                            Moje hodnocení
                        </a>
                    </li>
            <?php } ?>

            <?php if (PermissionService::isUserLoggedIn() && (PermissionService::isUserGarant() || PermissionService::isUserLector())) { ?>
                <li class="nav-item <?php if (basename($_SERVER['PHP_SELF']) == 'vyucovane_kurzy.php')
                    echo 'active' ?>">
                        <a href="vyucovane_kurzy.php">
                            <i class="fa fa-graduation-cap"></i>
                            Vyučované kurzy
                        </a>
                    </li>
            <?php } ?>


        </ul>
    </div>
    <div class="container-fluid justify-content-start">
        <?php if (isset($_GET['success'])) { ?>
            <p style="color: green; text-align: center; margin: 0;"><?php echo $_GET['success'];?></p>
        <?php } ?>
        <?php if (isset($_GET['error'])) { ?>
            <p style="color: red; text-align: center; margin: 0;"><?php echo $_GET['error'];?></p>
        <?php } ?>
        <?php if (PermissionService::isUserLoggedIn()) { ?>
            <i class="fa fa-user"></i>
            <p class="text-white"><?php echo $_SESSION['login']; ?></p>
        <?php } ?>
    </div>

</nav>