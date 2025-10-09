<?php
    require_once '../../common/common.php';
    require_once '../../services/sanitize_service.php';
    require_once '../../services/login_service.php';

    $loginService = new LoginService();

    if (!$loginService->isEverythingSetForRegister()) {
        redirect("../register.php");
        exit();
    }    

    $loginService->register();
    redirect("../login.php");
    exit();