<?php
require "database.php";
require "utils.php";
session_start();

if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
}

$conn = connect();
$cost = 0;

if (isset($_SESSION['cart'])){
    $cart = array();
    foreach ($_SESSION['cart'] as $temp => $value) {
        if (isset($value['product_id'])){
            $cart['products'][] = $value;
            if (isset($value['ingredients'])){
                $cost += calcola_aggiunte($conn, $value['ingredients'], $value['product_id']);
            }
        }else if(isset($value['menu_id'])){
            $cart['menu'][] = $value;
        }
        $cost += get_product_cost($conn, $value['product_id']) * $value['qt'];
    }
    $receipt_id = receipt($conn, $_SESSION['loggedin'], $cost);
    order($conn, $cart, $receipt_id);
    // $_SESSION['cart'] = array();
}

?>
<html lang="it">
    <head>
        <title>L'angolo dell'informatico</title>
        <link rel="stylesheet" href="../style/order.css">
    </head>
    <body>
        <h1>Ricevuta</h1>
        <h3>Totale: <?php echo number_format($cost, 2)."€"?></h3>
        <form>
            <input type="button" value="Indietro" onclick="window.location.href='index.php'">
        </form>
    </body>
</html>
