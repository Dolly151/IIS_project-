<?php

require_once __DIR__ . '/../../common/common.php';
require_once __DIR__ . '/../../services/permission_service.php';
require_once __DIR__ . '/../../services/request_service.php';

PermissionService::requireRole(PermissionLevel::ANY);

$course_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$request_service = new RequestService();

if ($request_service->createCourseRegistrationRequest($course_id)) {
    redirect("../course_detail.php?id=" . urlencode($course_id) . "&success=Žádost o zápis do kurzu byla úspěšně vytvořena");
} else {
    redirect("../course_detail.php?id=" . urlencode($course_id) . "&error=Nepodařilo se vytvořit žádost o zápis do kurzu");
}

