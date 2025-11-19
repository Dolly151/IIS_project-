<?php 
    require_once('../common/common.php');

    make_header('WIS - registrace', 'register')
?>                

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container login-container py-5">
                <form action="actions/register_action.php" method="post">
                    <h1>Registrace</h1>
                    <hr>
                    <div class="form-group mb-2">
                        <label for="text" class="form-label">Login</label>
                        <input type="text" class="form-control" placeholder="Zadajte login" name="login">
                    </div>
                    <div class="form-group mb-2">
                        <label for="text" class="form-label">Křestní jméno</label>
                        <input type="text" class="form-control" placeholder="Zadejte jméno" name="firstName">
                    </div>
                    <div class="form-group mb-2">
                        <label for="text" class="form-label">Příjmení</label>
                        <input type="text" class="form-control" placeholder="Zadejte příjmení" name="lastName">
                    </div>
                    <div class="form-group mb-2">
                        <label for="text" class="form-label">Email</label>
                        <input type="text" class="form-control" placeholder="Zadejte email" name="email">
                    </div>
                    <div class="form-group mb-2">
                        <label for="text" class="form-label">Heslo</label>
                        <input type="password" class="form-control" placeholder="Zadajte heslo" name="pwd">
                    </div>
                    <div class="form-group mb-2">
                        <label for="text" class="form-label">Rodné číslo</label>
                        <input type="text" class="form-control" placeholder="Zadejte rodné číslo" name="rodneCislo">
                    </div>
                    <div class="form-group mb-2">
                        <label for="text" class="form-label">Datum narození</label>
                        <input type="date" class="form-control" placeholder="Zadejte datum narození" name="birthDate">
                    </div>
                    <div class="form-group mb-2">
                        <label for="text" class="form-label">Adresa</label>
                        <input type="text" class="form-control" placeholder="Zadejte adresu" name="address">
                    </div>
                    <div class="form-group my-4">
                        <button type="submit" class="btn btn-primary">Registrovat</button>
                    </div>
                    </div>
                </form> 
            </div>
        </main>
    </div>

</body>
</html>    