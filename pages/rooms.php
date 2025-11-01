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
                    <a href="add_room.php" class="type">Přidat novou místnost</a>
                </nav>
                <hr>
                <div class="container w-100 h-100">
                    <div class="row fw-bold">
                        <div class="col-6">Místnost</div>
                        <div class="col-6">Editace</div>
                    </div>

                    <?php foreach ($rooms as $room): ?>
                        <div class="row align-items-center py-1 border-bottom">
                            <div class="col-6"><?php echo htmlspecialchars($room['nazev'])?></div>
                            <div class="col-6">
                                <a href="room_edit.php?id=<?= $room['ID'] ?>" class="btn btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <form action="actions/delete_room_action.php" method="post" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $room['ID'] ?>">
                                    <button type="submit" class="btn btn-sm" onclick="return confirm('Opravdu chcete smazat tuto místnost?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

</body>
</html>    