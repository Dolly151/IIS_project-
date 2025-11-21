<?php
require_once('../common/common.php');
require_once('../services/room_service.php');

$roomService = new RoomService();

$rooms = method_exists($roomService, 'getAll')
    ? $roomService->getAll()
    : $roomService->getAllRooms();

make_header('WIS - vytvoření kurzu', 'course_create');
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container login-container py-5">
                <form action="actions/course_create_action.php" method="post">
                    <h1>Vytvoření kurzu</h1>
                    <hr>

                    <div class="form-group mb-2">
                        <label class="form-label">Zkratka</label>
                        <input type="text" class="form-control" placeholder="Zadejte zkratku" name="zkratka" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Název</label>
                        <input type="text" class="form-control" placeholder="Zadejte název" name="nazev" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Popis</label>
                        <input type="text" class="form-control" placeholder="Zadejte popis" name="popis" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Cena</label>
                        <input type="number" min="0" class="form-control" placeholder="Zadejte cenu" name="cena" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Limit</label>
                        <input type="number" min="1" class="form-control" placeholder="Zadejte limit" name="limit" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Vyberte den:</label>
                        <select id="den" class="form-control" name="den" required>
                            <option value="1">Pondělí</option>
                            <option value="2">Úterý</option>
                            <option value="3">Středa</option>
                            <option value="4">Čtvrtek</option>
                            <option value="5">Pátek</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Výuka od</label>
                        <input type="time" class="form-control" name="vyuka_od" required>
                        <label class="form-label">Výuka do</label>
                        <input type="time" class="form-control" name="vyuka_do" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label">Místnost</label>
                        <select id="room_id" name="room_id" class="form-control" required>
                            <option value="">— vyberte —</option>
                            <?php foreach ($rooms as $r): ?>
                                <option value="<?= (int)$r['ID'] ?>">
                                    <?= htmlspecialchars($r['nazev']) ?>
                                    <?php if (!empty($r['kapacita'])): ?>
                                        (kap. <?= (int)$r['kapacita'] ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary">Vytvořit kurz</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
