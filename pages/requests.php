<?php
    require_once '../common/common.php';
    require_once '../services/permission_service.php';
    require_once '../services/request_service.php';

    PermissionService::requireRole(PermissionLevel::ANY);

    $request_service = new RequestService();
    $requests_to_approve = $request_service->getRoleRelevantRequests();
    $my_requests = $request_service->getMyRequests();

    make_header('WIS - Žádosti', 'requests');
?>


<!-- todo: request page -->