<?php
    session_start();

    if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        header("Location: login.php");
    }
?>

<html lang="it">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../style/dashboard.css">
</head>
<body>
    <h1>DASHBOARD</h1>
    <p class="welcome">Benvenuto <?php echo $_SESSION['username']; ?>!</p>
    <form action="newProduct.php">
        <input type="submit" name="prod" value="Inserisci prodotti">
    </form>
    <form action="newMenu.php">
        <input type="submit" name="prod" value="Inserisci menu">
    </form>
    <form action="select.php">
        <input type="submit" name="prod" value="Visualizza utenti">
    </form>
    <form action="index.php">
        <input type="submit" value="Torna alla home"">
    </form>
</body>
</html>
