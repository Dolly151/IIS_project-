<?php

// je potreba do getu dat id zadosti

require_once('../../common/common.php');
require_once('../../services/permission_service.php');
require_once('../../services/request_service.php');

PermissionService::isUserLoggedIn();

$requestService = new RequestService();

if (isset($_GET['id'])) {
    if ($requestService->approveRequest($_GET['id'])) {
        redirect("../requests.php?success=Žádost byla úspěšně schválena&view=approve");
        exit();
    }
}
redirect("../requests.php?error=Nepodařilo se schválit žádost&view=approve");
exit();
