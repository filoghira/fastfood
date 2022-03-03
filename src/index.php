<?php
require "database.php";
session_start();

$conn = connect();

if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
}

function print_sizes($name, $sizes){
    $i = 0;
    foreach($sizes as $k2=> $v2) {
        echo "<input type='radio' name='".$name."'";
        if($i == 0) {
            echo " checked ";
        }
        switch ($v2['my_size']) {
            case 1:
                echo "value='S'>S - ". $v2['price']."€";
                break;
            case 2:
                echo "value='M'>M - ". $v2['price']."€";
                break;
            case 3:
                echo "value='L'>L - ". $v2['price']."€";
                break;
        }
        $i++;
    }
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
<form action="order.php" method="post">
    <h1>Prodotti singoli</h1>
    <table class="prodotti">
        <tr>
            <th>Nome</th>
            <th>Quantità</th>
            <th>Size</th>
        </tr>
<?php
foreach($products as $k=> $v) {
    $sizes = product_sizes($conn, $v['name']);
    echo "<tr>";
    echo "<td>" . $v['name'] . "</td>";
    echo "<td><input type='number' name='prod_".$v['id']."' min='0' max='10' value='0'></td>";
    echo "<td>";
    print_sizes("p_size_".$v['id'], $sizes);
    echo "</td>";
    echo "</tr>";
}
?>
    </table>
    <h1>Menu</h1>
    <table class="menu">
        <tr>
            <th>Nome</th>
            <th>Quantità</th>
            <th>Size</th>
        </tr>
<?php
    foreach($menu as $k=> $v) {
        $sizes = menu_sizes($conn, $v['name']);
        echo "<tr>";
        echo "<td>" . $v['name'] . "</td>";
        echo "<td><input type='number' name='menu_".$v['id']."' min='0' max='5' value='0'></td>";
        echo "<td>";
        print_sizes("m_size_".$v['id'], $sizes);
        echo "</td>";
        echo "</tr>";
        $contains = get_menu_composition($conn, $v['name']);
        foreach ($contains as $k2 => $v2){
            echo "<tr>";
            echo "<td></td>";
            echo "<td>".$v2['name']."</td>";
            echo "</tr>";
        }
    }
?>
    </table>
    <input class="button" type="submit" name="order" value="Ordina">
</form>
<?php
if(isset($_SESSION['username']) && isAdmin($conn, $_SESSION['username'])){
?>
<form class="dashboard" action="dashboard.php" method="post">
    <input class="button" type="submit" name="dashboard" value="Admin dashboard">
</form>
<?php }?>
<form action="login.php" method="post">
    <input class="button" type="submit" name="logout" value="Logout">
</form>
</body>
</html>
