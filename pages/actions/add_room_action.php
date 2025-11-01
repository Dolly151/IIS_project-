<?php
    require_once '../../common/common.php';
    require_once '../../services/sanitize_service.php';
    require_once '../../services/room_service.php';

    $roomService = new RoomService();

    if (!$roomService->isEverythingSetForNewRoom()) {
        redirect("../add_room.php?error=Neplatný požadavek");
        exit();
    }    

    $roomService->createRoom();
    redirect("../rooms.php?success=Místnost byla úspešne přidána!");
    exit();