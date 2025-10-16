<?php 
    require_once('../common/common.php');

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
                    <div class="form-group">
                        <label for="text" class="form-label">Zkratka</label>
                        <input type="text" class="form-control" placeholder="Zadejte zkratku" name="zkratka">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">název</label>
                        <input type="text" class="form-control" placeholder="Zadejte název" name="nazev">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">popis</label>
                        <input type="text" class="form-control" placeholder="Zadejte popis" name="popis">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">cena</label>
                        <input type="text" class="form-control" placeholder="Zadejte cenu" name="cena">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">limit</label>
                        <input type="text" class="form-control" placeholder="Zadejte limit" name="limit">
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Vytvořit kurz</button>
                    </div>
                </form> 
            </div>
        </main>
    </div>

</body>
</html>   