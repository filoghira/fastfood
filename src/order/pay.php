<?php
require "../database/database.php";
require "../utils/utils.php";
session_start();

if(!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
    header("Location: login.php");
}

$conn = connect();
$cost = 0;
$cart = array();

if (isset($_SESSION['cart'])){
    if(isset($_SESSION['cart']['products'])){
        foreach ($_SESSION['cart']['products'] as $temp => $value) {
            $cart['products'][] = $value;
            if (isset($value['ingredients'])){
                $cost += calcola_aggiunte($conn, $value['ingredients'], $value['product_id']);
            }
            $cost += get_productCost($conn, $value['product_id']) * $value['qt'];
        }
    }
    if (isset($_SESSION['cart']['menu'])){
        foreach ($_SESSION['cart']['menu'] as $temp => $value) {
            $cart['menu'][] = $value;
            $cost += get_menuCost($conn, $value['menu_id']) * $value['qt'];
        }
    }
}
?>
<html lang="it">
    <head>
        <title>L'angolo dell'informatico</title>
        <link rel="stylesheet" href="../../style/order.css">
    </head>
    <body>
    <?php
    if (get_userWallet($conn, $_SESSION['user_id']) < $cost){
        ?>
        <p class="error">Non hai abbastanza soldi sulla tua carta di credito</p>
        <?php
    }else {
        $receipt_id = receipt($conn, $_SESSION['user_id'], $cost);
        order($conn, $cart, $receipt_id);
        pay($conn, $_SESSION['user_id'], $cost);
        $_SESSION['cart'] = array();
        ?>
        <h1>Ricevuta</h1>
        <h3>Totale: <?php echo number_format($cost, 2)."€"?></h3>
        <?php
    }
    ?>
    <form>
        <input type="button" value="Indietro" onclick="window.location.href='../index.php'">
    </form>
    </body>
</html>
