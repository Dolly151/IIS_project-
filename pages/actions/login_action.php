<?php
    require_once '../../common/common.php';
    require_once '../../services/sanitize_service.php';
    require_once '../../services/login_service.php';

    $loginService = new LoginService();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if ($loginService->authenticate()) {
            redirect('../overview.php?success=Byli jste úspěšně přihlášeni');
            exit();
        } else {
            $error_message = "Neplatný login nebo heslo.";
            redirect('../login.php?error=' . urlencode($error_message));
            exit();
        }
    } else {
        redirect('../login.php?error=Neplatný požadavek');
        exit();
    }