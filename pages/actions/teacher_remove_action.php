<?php
require_once '../../common/common.php';
require_once '../../services/permission_service.php';
require_once '../../services/teacher_service.php';

PermissionService::requireRole(PermissionLevel::GARANT);

$courseId = (int)($_POST['course_id'] ?? 0);
$userId   = (int)($_POST['user_id'] ?? 0);

if ($courseId > 0 && $userId > 0) {
    $svc = new TeacherService();
    if ($svc->removeTeacher($courseId, $userId)) {
        redirect('/pages/course_teachers.php?id='.$courseId.'&success='.urlencode('Lektor odebrán.'));
        exit;
    }
    redirect('/pages/course_teachers.php?id='.$courseId.'&error='.urlencode('Nepodařilo se odebrat lektora.'));
    exit;
}
redirect('/pages/overview.php?error='.urlencode('Neplatná data požadavku'));
exit;
