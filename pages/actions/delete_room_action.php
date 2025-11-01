<?php
    require_once '../../common/common.php';
    require_once '../../services/sanitize_service.php';
    require_once '../../services/room_service.php';

    $roomService = new RoomService();
    $id = $_POST['id'] ?? null;

    if (!$roomService->deleteRoom($id)) {
        redirect("../rooms.php?error=Neplatný požadavek");
        exit();
    }    

    redirect("../rooms.php?success=Místnost byla úspešne zmazána!");
    exit();