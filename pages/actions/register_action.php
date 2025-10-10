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
    redirect("../login.php?success=Byli jste úspěšně zaregistrováni, nyní se můžete přihlásit");
    exit();