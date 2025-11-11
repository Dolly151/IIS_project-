<?php
require_once '../../common/common.php';
require_once '../../services/permission_service.php';
require_once '../../services/teacher_service.php';

PermissionService::requireRole(PermissionLevel::GARANT);

$courseId = (int)($_POST['course_id'] ?? 0);
$userId   = (int)($_POST['user_id'] ?? 0);

if ($courseId > 0 && $userId > 0) {
    $svc = new TeacherService();
    if ($svc->addTeacher($courseId, $userId)) {
        redirect('../course_teachers.php?id='.$courseId.'&success='.urlencode('Lektor přidán.'));
        exit;
    }
    // buď není lektor, nebo už existuje vazba (unikátní klíč)
    redirect('../course_teachers.php?id='.$courseId.'&error='.urlencode('Uživatel není lektor nebo už je v kurzu.'));
    exit;
}
redirect('../overview.php?error='.urlencode('Neplatná data požadavku'));
exit;
