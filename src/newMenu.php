<?php
require 'database.php';
require 'utils.php';
session_start();
$conn = connect();

if((!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) && isAdmin($conn, $_SESSION['username'])) {
    header("Location: login.php");
}
?>

<html lang="it">
<head>
    <title>FastFood</title>
    <link rel="stylesheet" type="text/css" href="../style/newMenu.css">
</head>
<body>
<?php
if(isset($_POST['addProd'])) {
    $_SESSION['newMenu']['name'] = $_POST['nome'];
    $_SESSION['newMenu']['price'] = $_POST['prezzo'];
    $_SESSION['newMenu']['size'] = $_POST['size'];

    $prods = select_products($conn);
    ?>
    <form method="post">
        <label>
            Prodotti
            <select name="product">
                <?php
                foreach ($prods as $k=>$v) {
                    echo "<option value='".$v['id']."'>".$v['name']."</option>";
                }
                ?>
            </select>
        </label>
        <input class="button" type="submit" name="dim" value="Prosegui">
        <input class="button" type="submit" name="cancel" value="Annulla">
    </form>
<?php
}else if (isset($_POST['dim'])) {
    // Seleziona la dimensione del prodotto
    $name = get_product_name($conn, $_POST['product']);
    $sizes = product_sizes($conn, $name);
    ?>
    <h1>Seleziona la dimensione - <?php echo $name?></h1>
    <form method="post">
        <label>
            <select name="product">
                <?php
                foreach ($sizes as $k => $v){
                    try {
                        echo "<option value='" . get_sized_product($conn, $_POST['product'], $v['my_size']) . "'>" . sizeLetter((int)$v['my_size']) . "</option>";
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
                ?>
            </select>
        </label>
        <label>
            Quantità
            <input type="number" name="quantity" min="1" value="1">
        </label>
        <input class="button" type="submit" name="added" value="Aggiungi">
        <input class="button" type="submit" name="cancel" value="Annulla">
    </form>
<?php
}else{
?>
<h1>Inserisci un nuovo prodotto</h1>
<form method="post">
    <label for="nome">Nome</label>
    <br>
    <input type="text" name="nome" id="nome" <?php
    if(isset($_SESSION['newMenu']['name'])) {
        echo "value='".$_SESSION['newMenu']['name']."'";
    }
    ?> required>
    <br>
    <label for="prezzo">Prezzo</label>
    <br>
    <input type="number" name="prezzo" id="prezzo" min="0.01" <?php
    if(isset($_SESSION['newMenu']['price'])) {
        echo "value='".$_SESSION['newMenu']['price']."'";
    }
    ?> required>
    <br>
    <label for="size">Dimensione</label>
    <br>
    <select name="size" id="size">
        <option value="1" <?php if(isset($_SESSION['newMenu']['size']) && $_SESSION['newMenu']['size']==1) {
            echo "selected"; } ?>>Small</option>
        <option value="2" <?php if(isset($_SESSION['newMenu']['size']) && $_SESSION['newMenu']['size']==2) {
            echo "selected"; } ?>>Medium</option>
        <option value="3" <?php if(isset($_SESSION['newMenu']['size']) && $_SESSION['newMenu']['size']==3) {
            echo "selected"; } ?>>Large</option>
    </select>
    <br>
    <table>
        <tr>
            <th>Nome</th>
            <th>Dimensione</th>
            <th>Prezzo</th>
            <th>Quantità</th>
        </tr>
        <?php
        if (isset($_POST['added'])){
            $_SESSION['newMenu']['prods'][$_POST['product']] += $_POST['quantity'];
        }
        foreach ($_SESSION['newMenu']['prods'] as $id => $qt ){
            echo "<tr>";
            echo "<td>".get_product_name($conn, $id)."</td>";
            echo "<td>".sizeLetter((int)get_product_size($conn, $id))."</td>";
            echo "<td>".get_product_price($conn, $id)."</td>";
            echo "<td>".$qt."</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <br>
    <input class="button" type="submit" value="Aggiungi prodotto" name="addProd">
    <br>
    <input class="button" type="submit" name="insert" value="Inserisci">
</form>
<?php
    if (isset($_POST['insert'])){
        $menuID = insert_menu($conn, $_SESSION['newMenu']['name'], $_SESSION['newMenu']['price'], $_SESSION['newMenu']['size']);
        foreach ($_SESSION['newMenu']['prods'] as $id => $qt) {
            for ($i=0; $i<$qt; $i++) {
                insert_menu_composition($conn, $menuID, $id);
            }
        }
        unset($_SESSION['newMenu']);
        echo "<h1>Menu inserito correttamente</h1>";
    }
}
?>
<form action="dashboard.php">
    <input class="button" type="submit" value="Torna alla dashboard">
</form>
</body>
</html>

