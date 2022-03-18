<?php
require "database/database.php";
session_start();
$conn = connect();

if (isset($_POST['logout'])) {
    unset($_SESSION['username'], $_SESSION['user_id']);
}

if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    header("Location: index.php");
    die();
}

if (isset($_POST['username'], $_POST['password'])) {
    if (isset($_POST['login'])) {
        $_SESSION['user_id'] = login($conn, $_POST['username'], $_POST['password']);
        if ($_SESSION['user_id'] !== null) {
            $_SESSION['username'] = $_POST['username'];
            header("Location: index.php");
            die();
        }

        $_SESSION['login_error'] = "Username o password errati";
    } else {
        if (isset($_POST['register'])) {
            $_SESSION['user_id'] = register($conn, $_POST['username'], $_POST['password']);
            if ($_SESSION['user_id'] !== null) {
                $_SESSION['username'] = $_POST['username'];
                header("Location: index.php");
                die();
            }

            $_SESSION['login_error'] = "Errore di registrazione";
        }
    }
}

$username = $password = "";
?>

<html lang="it">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>
<img src="../images/Logo.png" alt="logo">
<form class="login" action="login.php" method="post">
    <h1 class="title">LOGIN</h1>
    <label for="username">USERNAME</label>
    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>">
    <br>
    <label for="password">PASSWORD</label>
    <input type="password" name="password" id="password">
    <br>
    <input type="submit" name="login" value="Login">
    <input type="submit" name="register" value="Register">
</form>
<h2 class="error">
    <?php
    if (isset($_SESSION['login_error'])) {
        echo $_SESSION['login_error'];
        unset($_SESSION['login_error']);
    }
    ?>
</h2>
</body>
</html>
