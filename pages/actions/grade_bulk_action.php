<?php
require_once __DIR__ . '/../../common/common.php';
require_once __DIR__ . '/../../services/permission_service.php';
require_once __DIR__ . '/../../services/grades_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("../overview.php?error=" . urlencode("Neplatný požadavek")); exit;
}
if (!PermissionService::isUserLoggedIn()) {
    redirect("../login.php"); exit;
}

$courseId = (int)($_POST['course_id'] ?? 0);
$terminId = (int)($_POST['termin_id'] ?? 0);
$points   = $_POST['points'] ?? []; // očekáváme asociativní pole points[studentId] => num

$gs    = new GradesService();
$userId = (int)($_SESSION['user_id'] ?? 0);

$allowed = PermissionService::isUserAdmin() || PermissionService::isUserGarant() || $gs->isTeacherOfCourse($courseId, $userId);
if (!$allowed || $courseId<=0 || $terminId<=0 || !is_array($points)) {
    redirect("../grade_add.php?id=$courseId&termin=$terminId&error=" . urlencode("Chybná data / oprávnění")); exit;
}

$okAll = true;
foreach ($points as $sid => $val) {
    if ($val === '' || $val === null) continue;           // prázdné řádky přeskoč
    if (!is_numeric($val)) { $okAll = false; continue; }  // invalidní číslo
    $ok = $gs->upsertPoints($terminId, (int)$sid, (int)$val);
    if (!$ok) $okAll = false;
}

redirect("../gradebook.php?id=$courseId&" . ($okAll ? "success=Uloženo" : "error=Část se nepodařila"));
exit;
