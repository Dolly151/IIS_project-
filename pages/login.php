<?php 
    require_once('../common/common.php');

    $error_login = $error_password = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['login'])) {
            $error_login = "Toto pole je povinné";
        } else {
            $login = $_POST['login'];
        }
        if (empty($_POST['pwd'])) {
            $error_password = "Toto pole je povinné";
        }
        else {
            $password = $_POST['pwd'];
        }
    }
    
    make_header('WIS - přihlásení', 'login')
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container login-container">
                <!--TODO: add action file-->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
                    <h3 class="text-center">Přihlásení</h3>
                    <div class="form-group">
                        <label for="text" class="form-label">Login</label>
                        <input type="text" class="form-control <?php echo !empty($error_login) ? 'is-invalid' : ''; ?>" placeholder="Zadajte login" name="login">
                        <?php if (!empty($error_login)): ?>
                            <div class="invalid-feedback"><?php echo $error_login; ?></div>
                        <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="text" class="form-label">Heslo</label>
                        <input type="password" class="form-control <?php echo !empty($error_password) ? 'is-invalid' : ''; ?>" placeholder="Zadajte heslo" name="pwd">
                        <?php if (!empty($error_password)): ?>
                            <div class="invalid-feedback"><?php echo $error_password; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Přihlásit</button>
                    </div>
                    <div class="form-group text-center">
                        <a href="" class="text-center">Registrovat se</a>
                    </div>
                </form> 
            </div>
        </main>
    </div>

</body>
</html>
