<?php

require_once __DIR__ . '/../../common/common.php';
require_once __DIR__ . '/../../services/course_creation_service.php';

$courseCreateService = new CourseCreationService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($courseCreateService->isEverythingSetForCreate()) {
        $courseId = $courseCreateService->createCourse();
        if ($courseId) {
            redirect("../course_detail.php?id=" . $courseId . "&success=Žádost o nový kurz byla vytvořena");
            exit();
        } else {
            $error_message = "Chyba při vytváření kurzu.";
            redirect("../course_create.php?error=" . urlencode($error_message));
            exit();
        }
    } else {
        redirect("../course_create.php?error=Neplatný požadavek");
        exit();
    }
} else {
    redirect("../course_create.php?error=Neplatný požadavek");
    exit();
}