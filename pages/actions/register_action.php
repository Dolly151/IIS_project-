<?php
    require_once '../../common/common.php';
    require_once '../../services/sanitize_service.php';
    require_once '../../services/login_service.php';

    $loginService = new LoginService();

    if (!$loginService->isEverythingSetForRegister()) {
        redirect("../register.php?error=Neplatný požadavek");
        exit();
    }    

    $loginService->register();
    if ($_SESSION['role'] == PermissionLevel::ADMIN->value) {
        redirect("../users.php?success=Uživatel byl úspešne zaregistrován");
    }   
    else {
        redirect("../login.php?success=Byli jste úspěšně zaregistrováni, nyní se můžete přihlásit");
    }
    exit();