<?php

require_once('../../common/common.php');
require_once('../../services/permission_service.php');
require_once('../../services/profile_service.php');

PermissionService::isUserLoggedIn();

$profileService = new ProfileService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileService->updateUserProfile();
    if (isset($_POST['pwd']) && !empty($_POST['pwd'])) {
        
        $profileService->updatePassword($_POST['pwd']);
    }
    redirect("../profile.php?success=Údaje byly úspěšně aktualizovány");
    exit();
}
redirect("../profile_edit.php?error=Neplatný požadavek");
exit();
