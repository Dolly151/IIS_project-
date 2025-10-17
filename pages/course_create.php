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
                    <div class="form-group">
                        <label for="text" class="form-label">Vyberte den: </label>
                        <select id="den" class="form-control" name="den">
                            <option value="1">Pondělí</option>
                            <option value="2">Úterý</option>
                            <option value="3">Středa</option>
                            <option value="4">Čtvrtek</option>
                            <option value="5">Pátek</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">vyuka od</label>
                        <input type="time" class="form-control" placeholder="Zadejte vyuku od" name="vyuka_od">
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">vyuka do</label>
                        <input type="time" class="form-control" placeholder="Zadejte vyuku do" name="vyuka_do">
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