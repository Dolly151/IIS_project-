<?php 
    require_once '../common/common.php';
    require_once '../data/repository_factory.php';
    require_once '../services/permission_service.php';

    $repository = RepositoryFactory::create();
    $permissionService = new PermissionService();
    
    make_header('WIS - uživatele', 'requests');
?>                

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container profile d-flex flex-column h-auto py-5">
                <h1>Přidat novou místnost</h1>
                <hr>
                <form action="actions/add_room_action.php" method="post">
                    <div class="form-group">
                        <label for="text" class="form-label">Název</label>
                        <input type="text" class="form-control" placeholder="Zadajte název místnosti" name="nazev">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Typ</label>
                        <input type="text" class="form-control" placeholder="Zadajte typ místnosti" name="typ">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Popis</label>
                        <input type="text" class="form-control" placeholder="Zadajte popis místnosti" name="popis">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Kapacita</label>
                        <input type="text" class="form-control" placeholder="Zadajte kapacitu místnosti" name="kapacita">
                    </div>    
                    <div class="form-group text-center my-5">
                        <button type="submit" class="btn btn-primary">Přidat</button>
                    </div>
                </form>
            </div>
        </main>

</body>
</html>    