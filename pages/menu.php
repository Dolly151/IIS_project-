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
            <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == 'profile.php') echo 'active' ?>">
                <a href="profile.php">
                    <i class="fa fa-user"></i> 
                    Profil
                </a>
            </li>
            <?php } ?>

            <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserStudent()) { ?>
                <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == 'schedule.php') echo 'active' ?>">
                    <a href="schedule.php">
                        <i class="fa fa-television"></i> 
                        Rozvrh
                    </a>
                </li>
            <?php } ?>

            <?php if (!PermissionService::isUserLoggedIn()) { ?>
            <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == 'login.php') echo 'active' ?>">
                <a href="login.php">
                    <i class="fa fa-sign-in"></i> 
                    Přihlásení
                </a>
            </li>
            <?php } ?>
            <?php if (PermissionService::isUserLoggedIn()) { ?>
            <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == 'requests.php') echo 'active'; ?>">
                <a href="requests.php">
                    <i class="fa fa-sign-in"></i> 
                    Žádosti
                </a>
            </li>
            <?php } ?>

            <!-- ADMIN can see and edit users-->
            <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserAdmin()) { ?>
            <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == 'users.php') echo 'active' ?>">
                <a href="users.php">
                    <i class="fa fa-users"></i> 
                    Uživatele
                </a>
            </li>
            <?php } ?>

            <!-- ADMIN can see an edit rooms-->
            <?php if (PermissionService::isUserLoggedIn() && PermissionService::isUserAdmin()) { ?>
            <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == 'rooms.php') echo 'active' ?>">
                <a href="rooms.php">
                    <i class="fa fa-university"></i> 
                    Místnosti
                </a>
            </li>
            <?php } ?>

        </ul>
    </div>
    <div class="container-fluid justify-content-start">
        <?php if (PermissionService::isUserLoggedIn()) { ?>
            <i class="fa fa-user"></i>  
            <p class="text-white"><?php echo $_SESSION['login'];?></p>
        <?php } ?>
    </div>
    
</nav>