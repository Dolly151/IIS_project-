<?php
require_once __DIR__ . '/../services/permission_service.php';
?>

<nav class="navbar">
    <a href="overview.php"><img src="../assets/img/logo.png" alt="FIT VUT logo" class="logo"></a>
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
        <!--
        <li class="nav-item <?php if(basename($_SERVER['PHP_SELF']) == 'schedule.php') echo 'active' ?>">
            <a href="schedule.php">
                <i class="fa fa-television"></i> 
                Rozvrh
            </a>
        </li>-->
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

    </ul>
</nav>