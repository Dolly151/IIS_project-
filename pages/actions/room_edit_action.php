<?php
    require_once('../../common/common.php');
    require_once('../../services/permission_service.php');
    require_once('../../services/room_service.php');

    $roomService = new RoomService();
    $id = $_GET['id'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $roomService->updateRoom($id);
        redirect("../rooms.php?success=Údaje byly úspěšně aktualizovány");
        exit();
    }
    redirect("../room_edit.php?error=Neplatný požadavek");
    exit();
