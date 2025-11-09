<?php
require_once('../common/common.php');
require_once('../services/permission_service.php');
require_once('../services/teacher_service.php');

PermissionService::requireRole(PermissionLevel::GARANT);

$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($courseId <= 0) { redirect('/pages/overview.php?error='.urlencode('Chybí ID kurzu')); exit; }

$svc = new TeacherService();

// ochrana: i kdyby někdo trefil URL, TeacherService stejně uvnitř kontroluje garanta
$teachers  = $svc->listTeachers($courseId);
$cands     = $svc->listLecturerCandidates();

make_header('Lektoři kurzu', 'main');
?>
<body>
<div class="wrapper d-flex">
  <header><?php include __DIR__ . '/menu.php'; ?></header>
  <main class="container py-4">
    <h1>Lektoři kurzu #<?= (int)$courseId ?></h1>
    <hr>

    <form class="row g-2 mb-4" method="post" action="/pages/actions/teacher_add_action.php">
      <input type="hidden" name="course_id" value="<?= (int)$courseId ?>">
      <div class="col-md-6">
        <label class="form-label">Přidat lektora</label>
        <select class="form-select" name="user_id" required>
          <option value="" disabled selected>— vyber lektora (role LECTOR) —</option>
          <?php foreach ($cands as $u): ?>
            <option value="<?= (int)$u['ID'] ?>"><?= htmlspecialchars($u['jmeno'].' '.$u['prijmeni'].' ('.$u['login'].')') ?></option>
          <?php endforeach; ?>
        </select>
        <div class="form-text">Seznam zobrazuje pouze uživatele s rolí <strong>LECTOR</strong>.</div>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary" type="submit">Přidat</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead><tr><th>Jméno</th><th>Login</th><th>Role</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($teachers as $t): ?>
            <tr>
              <td><?= htmlspecialchars($t['jmeno'].' '.$t['prijmeni']) ?></td>
              <td><?= htmlspecialchars($t['login']) ?></td>
              <td><?= (int)$t['role'] ?></td>
              <td>
                <form method="post" action="/pages/actions/teacher_remove_action.php" onsubmit="return confirm('Odebrat tohoto lektora?');">
                  <input type="hidden" name="course_id" value="<?= (int)$courseId ?>">
                  <input type="hidden" name="user_id" value="<?= (int)$t['ID'] ?>">
                  <button class="btn btn-outline-danger btn-sm" type="submit">Odebrat</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($teachers)): ?>
            <tr><td colspan="4" class="text-muted">Zatím žádní lektoři.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
