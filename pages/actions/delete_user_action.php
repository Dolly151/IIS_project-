<?php 

    require_once '../../common/common.php';
    require_once '../../services/login_service.php';

    $loginService = new LoginService();

    if ($loginService->deleteAccount()) {
        $loginService->logout();
    }
    redirect("../overview.php");
    exit();