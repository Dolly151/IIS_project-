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

$terms    = $svc->getCourseTerms($courseId);             // sloupce (dynamické)
$students = $svc->listEnrolledStudents($courseId);       // řádky
$matrix   = $svc->getGradesMatrix($courseId);            // hodnoty

make_header('WIS – Seznam studentů', 'grades');
?>
<body>
<div class="wrapper d-flex">
  <header><?php include __DIR__ . '/menu.php'; ?></header>
  <main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="m-0">Seznam studentů – kurz #<?= (int)$courseId ?></h1>
      <a class="btn btn-primary" href="grade_add.php?id=<?= (int)$courseId ?>">Přidat hodnocení</a>
    </div>

    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>Student</th>
            <th>Email</th>
            <?php foreach ($terms as $t): ?>
              <th><?= htmlspecialchars($t['nazev']) ?></th>
            <?php endforeach; ?>
            <th>Součet</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($students as $s):
                $sid = (int)$s['ID'];
                $sum = 0;
          ?>
          <tr>
            <td><?= htmlspecialchars($s['jmeno'].' '.$s['prijmeni']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <?php foreach ($terms as $t):
                  $tid = (int)$t['ID'];
                  $val = $matrix[$sid][$tid]['body'] ?? '';
                  if ($val !== '') $sum += (int)$val;
            ?>
              <td><?= $val === '' ? '—' : (int)$val ?></td>
            <?php endforeach; ?>
            <td><strong><?= (int)$sum ?></strong></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (empty($students)): ?>
        <div class="alert alert-info">V kurzu nejsou zapsaní studenti.</div>
      <?php endif; ?>
    </div>
  </main>
</div>
</body>
</html>
