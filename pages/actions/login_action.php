<?php
    require_once '../../common/common.php';
    require_once '../../services/sanitize_service.php';
    require_once '../../services/login_service.php';

    $loginService = new LoginService();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if ($loginService->authenticate()) {
            header('Location: ../overview.php');
            exit();
        } else {
            $error_message = "Neplatný login nebo heslo.";
            header('Location: ../login.php?error=' . urlencode($error_message));
            exit();
        }
    } else {
        header('Location: ../login.php');
        exit();
    }