<?php
require "database/database.php";
require "utils/utils.php";
session_start();
$conn = connect();

if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
    header("Location: login.php");
}

$products = select_products($conn);
$menu = select_menu($conn);
?>
<html lang="it">
<head>
    <title>L'angolo dell'informatico</title>
    <link rel="stylesheet" href="../style/index.css">
</head>
<body>
<table>
    <tr>
        <th>Nome</th>
        <th>Prezzo</th>
        <th>Quantità</th>
    </tr>
    <?php
    if (isset($_SESSION['cart']['products'])) {
        foreach ($_SESSION['cart']['products'] as $item => $v) {
            echo "<tr>";
            echo "<td>" . get_product_name($conn, $v['product_id']) . "</td>";
            echo "<td>" . number_format(get_productPrice($conn, $v['product_id']) + calcola_aggiunte($conn, $v['ingredients'], $v['product_id']), 2) . "€" . "</td>";
            echo "<td>" . $v['qt'] . "</td>";
            echo "</tr>";
        }
    }
    if (isset($_SESSION['cart']['menu'])) {
        foreach ($_SESSION['cart']['menu'] as $item => $v) {
            echo "<tr>";
            echo "<td>" . get_menuName($conn, $v['menu_id']) . "</td>";
            echo "<td>" . number_format(get_menuPrice($conn, $v['menu_id']), 2) . "€" . "</td>";
            echo "<td>" . $v['qt'] . "</td>";
            echo "</tr>";
        }
    }
    ?>
</table>
<form>
    <input class="button" type="button" onclick="window.location.href='order/product.php'" value="Aggiungi prodotto">
    <input class="button" type="button" onclick="window.location.href='order/menu.php'" value="Aggiungi menu">
    <input class="button" type="button" onclick="window.location.href='order/pay.php'" value="Paga">
    <?php
    if (isset($_SESSION['username']) && isAdmin($conn, $_SESSION['username'])) {
        ?>
        <input class="button" type="button" onclick="window.location.href='administrator/dashboard.php'"
               name="dashboard" value="Admin dashboard">
    <?php } ?>
</form>
<form action="login.php" method="post">
    <input class="button" type="submit" name="logout" value="Logout">
</form>
</body>
</html>
