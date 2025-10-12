<?php 
    require_once('../common/common.php');
    require_once('../services/permission_service.php');
    require_once('../services/profile_service.php');

    PermissionService::requireRole(PermissionLevel::ANY);

    $id = $_GET['id'] ?? null;

    if ($_SESSION['role'] != PermissionLevel::ADMIN->value) {
        PermissionService::isUserThisId((int)$id);
    }

    $profileService = new ProfileService();
    $userDetails = $profileService->getUserDetail($id);

    make_header('WIS - úprava profilu', 'profile_edit')
?>                

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container login-container py-5">
                <form action="actions/profile_edit_action.php" method="post">
                    <h1>Úprava profilu</h1>
                    <hr>
                    <div class="form-group">
                        <label for="text" class="form-label">Křestní jméno</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userDetails['jmeno']); ?>" name="firstName">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Příjmení</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userDetails['prijmeni']); ?>" name="lastName">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userDetails['email']); ?>" name="email">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Heslo</label>
                        <input type="password" class="form-control" placeholder="Zadajte nové heslo" name="pwd">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Rodné číslo</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userDetails['rodne_cislo']); ?>" name="rodneCislo">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Datum narození</label>
                        <input type="date" class="form-control" value="<?php echo htmlspecialchars($userDetails['datum_narozeni']); ?>" name="birthDate">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Adresa</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($userDetails['adresa']); ?>" name="address">
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Uložit</button>
                        <a href="profile.php" class="btn btn-primary">Zpět na profil</a>
                    </div>
                </form> 
            </div>
        </main>
    </div>

</body>
</html>    