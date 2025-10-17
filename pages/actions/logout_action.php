<?php    
    require_once '../../common/common.php';
    require_once '../../services/login_service.php';

    $loginService = new LoginService();

    $loginService->logout();
    redirect("../login.php?success=Byli jste úspěšně odhlášeni");
    exit();