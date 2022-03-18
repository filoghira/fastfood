<?php
require '../database/database.php';
session_start();
$conn = connect();

if ((!isset($_SESSION['user_id']) || !$_SESSION['user_id']) && isAdmin($conn, $_SESSION['username'])) {
    header("Location: login.php");
}
?>

<html lang="it">
<head>
    <title>Visualizza</title>
    <link rel="stylesheet" href="../../style/select.css">
</head>
<body>
<h1>Elenco utenti</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Lvl. Autorizzazione</th>
    </tr>
    <?php
    $users = select_users($conn);
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['auth_level'] . "</td>";
        echo "</tr>";
    }
    ?>
</table>
<p>NB. 0=utente normale, 1=admin</p>
<form action="dashboard.php">
    <input class="button" type="submit" value="Torna alla dashboard">
</form>
</body>
</html>