<?php
require_once __DIR__ . '/../../common/common.php';
require_once __DIR__ . '/../../services/permission_service.php';
require_once __DIR__ . '/../../services/grades_service.php';
require_once __DIR__ . '/../../services/term_service.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("../taught_courses.php?error=" . urlencode("Neplatný požadavek")); exit;
}
if (!PermissionService::isUserLoggedIn()) {
    redirect("../login.php"); exit;
}

$courseId = (int)($_POST['course_id'] ?? 0);
$uid      = (int)($_SESSION['user_id'] ?? 0);

$gs = new GradesService();
$allowed = (PermissionService::isUserAdmin() || PermissionService::isUserGarant() || $gs->isTeacherOfCourse($courseId, $uid));
if (!$allowed || $courseId <= 0) {
    redirect("../taught_courses.php?error=" . urlencode("Přístup zamítnut / chybné ID kurzu")); exit;
}

$svc = new TermService();
if (!$svc->isValidForCreate()) {
    redirect("../term_create.php?id=$courseId&error=" . urlencode("Vyplňte všechna povinná pole.")); exit;
}

$ok = $svc->createTerm($courseId);
if ($ok) {
    // po vytvoření klidně rovnou na seznam studentů, aby bylo vidět termín i v přehledu
    redirect("../gradebook.php?id=$courseId&success=" . urlencode("Termín vytvořen."));
} else {
    redirect("../term_create.php?id=$courseId&error=" . urlencode("Nepodařilo se uložit termín."));
}
exit;
