<?php 
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>WIS - přihlásení</title>
</head>

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
                    <div class="form-group">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Přihlásit</button>
                        </div>
                    </div>
                </form> 
            </div>
        </main>
    </div>

</body>
</html>
