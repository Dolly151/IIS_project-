<?php 
    require_once('../common/common.php');
    require_once('../services/permission_service.php');
    require_once('../services/room_service.php');

    PermissionService::requireRole(PermissionLevel::ADMIN);

    $id = $_GET['id'] ?? null;

    $roomService = new RoomService();
    $roomDetails = $roomService->getRoomDetail($id);

    make_header('WIS - Úprava místnosti', 'room_edit')
?>            

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container d-flex flex-column h-auto py-5">
                <h1>Úprava místnosti</h1>
                <hr>
                <form action="actions/room_edit_action.php?id=<?php echo urlencode($id); ?>" method="post">
                    <div class="form-group">
                        <label for="text" class="form-label">Název</label>
                        <input type="text" class="form-control" placeholder="Zadajte název místnosti" name="nazev" value="<?php echo htmlspecialchars($roomDetails['nazev'])?>">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Typ</label>
                        <input type="text" class="form-control" placeholder="Zadajte typ místnosti" name="typ" value="<?php echo htmlspecialchars($roomDetails['typ'])?>">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Popis</label>
                        <input type="text" class="form-control" placeholder="Zadajte popis místnosti" name="popis" value="<?php echo htmlspecialchars($roomDetails['popis'])?>">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Kapacita</label>
                        <input type="text" class="form-control" placeholder="Zadajte kapacitu místnosti" name="kapacita" value="<?php echo htmlspecialchars($roomDetails['kapacita'])?>">
                    </div>    
                    <div class="form-group text-center my-5">
                        <button type="submit" class="btn btn-primary">Upravit detaily</button>
                    </div>
                </form>
            </div>
        </main>

</body>
</html>     