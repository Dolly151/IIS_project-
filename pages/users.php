<?php 
    require_once '../common/common.php';
    require_once '../data/repository_factory.php';
    require_once '../services/permission_service.php';

    $repository = RepositoryFactory::create();
    $permissionService = new PermissionService();

    $users = $repository->getAll('uzivatel');
    $id = $_GET['id'] ?? null;
    
    make_header('WIS - uživatele', 'requests');

    $view = $_GET['view'] ?? 'users';
?>                

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container profile d-flex flex-column h-auto py-5">
                <h1>Uživatele</h1>
                <nav>
                    <a href="?view=users" class="type">Uživatele</a>
                    <a href="register.php" class="type">Přidat</a>
                </nav>
                <hr>
                <div class="container w-100 h-100">
                    <div class="row fw-bold">
                        <div class="col-2">Login</div>
                        <div class="col-6">Jméno</div>
                        <div class="col-4">Editace</div>
                    </div>

                    <?php foreach ($users as $user): ?>
                        <div class="row align-items-center py-1 border-bottom">
                            <div class="col-2"><?= htmlspecialchars($user['login']) ?></div>
                            <div class="col-6"><?= htmlspecialchars($user['jmeno'] . ' ' . $user['prijmeni']) ?></div>
                            <div class="col-4">
                                <a href="profile_edit.php?id=<?= $user['ID'] ?>" class="btn btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <form action="actions/delete_user_action.php" method="post" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $user['ID'] ?>">
                                    <button type="submit" class="btn btn-sm" onclick="return confirm('Opravdu chcete smazat tohto uživatele?')">
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