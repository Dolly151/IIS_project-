<?php 
    require_once('../common/common.php');
    require_once('../services/permission_service.php');
    require_once('../services/room_service.php');

    PermissionService::requireRole(PermissionLevel::ADMIN);

    $room_service = new RoomService();

    $rooms = $room_service->getAllRooms();

    make_header('WIS - místnosti', 'requests')
?>                

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container profile d-flex flex-column h-auto py-5">
                <h1>Místnosti</h1>
                <nav>
                    <a href="?view=rooms" class="type">Místnosti</a>
                    <a href="?view=new" class="type">Zadat novou místnost</a>
                </nav>
                <hr>
            </div>
        </main>

</body>
</html>    