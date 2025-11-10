<?php
require_once('../common/common.php');
require_once('../services/permission_service.php');
require_once('../services/grades_service.php');

$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($courseId <= 0) { http_response_code(400); die('Chybí ID kurzu'); }

if (!PermissionService::isUserLoggedIn()) { http_response_code(401); die('Přihlaste se.'); }

$svc    = new GradesService();
$userId = (int)($_SESSION['user_id'] ?? 0);

$allowed = PermissionService::isUserLoggedIn()
    && (PermissionService::isUserAdmin() || PermissionService::isUserGarant() || $svc->isTeacherOfCourse($courseId, $userId));

if (!$allowed) { http_response_code(403); die('Přístup zamítnut'); }

$terms    = $svc->getCourseTerms($courseId);
$terminId = isset($_GET['termin']) ? (int)$_GET['termin'] : (int)($terms[0]['ID'] ?? 0);
$students = $svc->listEnrolledStudents($courseId);
$points   = $terminId ? $svc->getPointsForTerm($terminId) : [];

make_header('WIS – Přidat hodnocení', 'grades');
?>
<body>
<div class="wrapper d-flex">
  <header><?php include __DIR__ . '/menu.php'; ?></header>
  <main class="container py-4">
    <div class="d-flex align-items-center justify-content-between">
      <h1 class="m-0">Přidat hodnocení – kurz #<?= (int)$courseId ?></h1>
      <a class="btn btn-outline-secondary" href="gradebook.php?id=<?= (int)$courseId ?>">Zpět na seznam</a>
    </div>

    <form method="get" class="mt-3 mb-3">
      <input type="hidden" name="id" value="<?= (int)$courseId ?>">
      <label class="form-label">Termín:</label>
      <select name="termin" class="form-select" style="max-width: 360px;" onchange="this.form.submit()">
        <?php foreach ($terms as $t): ?>
          <option value="<?= (int)$t['ID'] ?>" <?= $terminId == (int)$t['ID'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($t['nazev']) ?><?= $t['datum'] ? ' – '.date('d.m.Y H:i', strtotime($t['datum'])) : '' ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <?php if (!$terminId): ?>
      <div class="alert alert-info">Nejprve vytvoř termín pro tento kurz.</div>
    <?php else: ?>
      <form action="actions/grade_bulk_action.php" method="post">
        <input type="hidden" name="course_id" value="<?= (int)$courseId ?>">
        <input type="hidden" name="termin_id" value="<?= (int)$terminId ?>">

        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead>
              <tr>
                <th>Student</th>
                <th>Email</th>
                <th style="width:160px;">Body (<?= htmlspecialchars($terms[array_search($terminId, array_column($terms,'ID'))]['nazev'] ?? '') ?>)</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($students as $s): $sid = (int)$s['ID']; ?>
              <tr>
                <td><?= htmlspecialchars($s['jmeno'].' '.$s['prijmeni']) ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td>
                  <input type="number" class="form-control" min="0" step="1"
                         name="points[<?= $sid ?>]" value="<?= htmlspecialchars($points[$sid] ?? '') ?>">
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          <button type="submit" class="btn btn-primary">Uložit hodnocení</button>
        </div>
      </form>
    <?php endif; ?>
  </main>
</div>
</body>
</html>
