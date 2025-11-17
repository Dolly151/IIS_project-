<?php
require_once('../common/common.php');
require_once('../services/permission_service.php');
require_once('../services/grades_service.php'); // kvůli kontrole, zda je lektor u kurzu
require_once('../services/room_service.php');

$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($courseId <= 0) { http_response_code(400); die('Chybí ID kurzu'); }

if (!PermissionService::isUserLoggedIn()) { http_response_code(401); die('Přihlaste se.'); }
$gs  = new GradesService();
$uid = (int)($_SESSION['user_id'] ?? 0);

$allowed = (PermissionService::isUserAdmin() || PermissionService::isUserGarant() || $gs->isTeacherOfCourse($courseId, $uid));
if (!$allowed) { http_response_code(403); die('Přístup zamítnut'); }

$roomService = new RoomService();
$rooms = method_exists($roomService, 'getAll') ? $roomService->getAll() : $roomService->getAllRooms();

make_header('WIS – Vypsat termín', 'main');
?>
<body>
<div class="wrapper d-flex">
  <header>
    <?php include __DIR__ . '/menu.php'; ?>
  </header>

  <main>
    <div class="container py-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0">Vypsat termín – kurz #<?= (int)$courseId ?></h1>
        <a class="btn btn-primary" href="taught_courses.php">Zpět na vyučované kurzy</a>
      </div>
      <hr>

      <form action="actions/term_create_action.php" method="post" class="d-flex flex-column gap-3" style="max-width:720px;">
        <input type="hidden" name="course_id" value="<?= (int)$courseId ?>">

        <div>
          <label class="form-label">Název termínu</label>
          <input type="text" class="form-control" name="nazev" placeholder="Např. Cvičení 1" required>
        </div>

        <div>
          <label class="form-label">Typ</label>
          <select name="typ" class="form-select" required>
            <option value="0">Přednáška</option>
            <option value="1">Cvičení</option>
            <option value="2">Zkouška</option>
            <option value="3">Domácí úkol</option>
            <option value="4">Projekt</option>
            <option value="5">Seminář</option>
          </select>
        </div>

        <div>
          <label class="form-label">Datum a čas</label>
          <input type="datetime-local" class="form-control" name="datum" required>
        </div>

        <div>
          <label class="form-label">Kapacita</label>
          <input type="number" class="form-control" name="kapacita" min="1" step="1" value="30" required>
        </div>

        <div>
          <label class="form-label">Místnost (volitelné)</label>
          <select name="room_id" class="form-select">
            <option value="">— nevybráno —</option>
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

        <div>
          <label class="form-label">Popis (volitelné)</label>
          <input type="text" class="form-control" name="popis" placeholder="Krátký popis">
        </div>

        <div class="pt-2">
          <button type="submit" class="btn btn-success">Vytvořit termín</button>
        </div>
      </form>
    </div>
  </main>
</div>
</body>
</html>
