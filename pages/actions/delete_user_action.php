<?php 

    require_once '../../common/common.php';
    require_once '../../services/login_service.php';

    $loginService = new LoginService();
    $id = $_POST['id'];

    if ($_SESSION['role'] != PermissionLevel::ADMIN->value && $_SESSION['user_id'] != $id) {
        exit();
    }

    if ($loginService->deleteAccount($id)) {
        // if user want to delete his own account
        if ($_SESSION['user_id'] == $id) {
        $loginService->logout();
        redirect("../login.php?success=Účet byl odstraněn");
        } 
        // if admin want to delete an account
        else {
            redirect("../users.php?success=Uživatel byl odstraněn");
        }
        exit();
    }

    redirect("../users.php?error=Nepodařilo se smazat uživatele");
    exit();
?>