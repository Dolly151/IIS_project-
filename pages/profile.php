<?php
    require_once('../common/common.php');
    require_once('../services/profile_service.php');
    require_once('../services/permission_service.php');

    PermissionService::requireRole(PermissionLevel::ANY);

    $profileService = new ProfileService();
    $userDetails = $profileService->getUserDetail();

    make_header('WIS- profile', 'profile');
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container profile d-flex flex-column h-50">
                <h1>Profil uživatele</h1>
                <div class="container details">
                    <?php foreach ($userDetails as $key => $value) { ?>
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><?php echo htmlspecialchars(ucfirst($key)); ?>:</strong>
                            </div>
                            <div class="col-sm-9">
                                <?php if ($key == 'role') {
                                    echo htmlspecialchars(PermissionService::permissionLevelToString(PermissionService::intToPermissionLevel($value)));
                                } else {
                                    echo htmlspecialchars($value);
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="container text-center">
                <a href="actions/logout_action.php" class="btn btn-primary">Odhlásit se</a>
                <a href="profile_edit.php?id=<?php echo urlencode($userDetails['ID']); ?>" class="btn btn-primary">Upravit</a>
                <a href="actions/delete_user_action.php" class="btn btn-primary">Smazat účet</a>
            </div>
            
        </main>
    </div>

</body>
</html>