<?php
require "database.php";
require "utils.php";
session_start();
$conn = connect();

if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
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
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item => $v) {
            echo "<tr>";
            echo "<td>".get_product_name($conn, $v['product_id'])."</td>";
            echo "<td>".number_format(get_product_price($conn, $v['product_id'])+calcola_aggiunte($conn, $v['ingredients'], $v['product_id']), 2)."€"."</td>";
            echo "<td>".$v['qt']."</td>";
            echo "</tr>";
        }
    }
    ?>
</table>
<form>
    <input class="button" type="button" onclick="window.location.href='product.php'" value="Aggiungi prodotto">
    <input class="button" type="button" onclick="window.location.href='./order.php'" value="Paga">
    <?php
    if(isset($_SESSION['username']) && isAdmin($conn, $_SESSION['username'])){
        ?>
        <input class="button" type="button" onclick="window.location.href='./dashboard.php'" name="dashboard" value="Admin dashboard">
    <?php }?>
</form>
<form action="login.php" method="post">
    <input class="button" type="submit" name="logout" value="Logout">
</form>
</body>
</html>
