<?php
require "../database/database.php";
require "../utils/utils.php";
session_start();
$conn = connect();
$prods = select_products($conn);
?>
<html lang="">
<head>
    <title>Angolo Informatico</title>
    <link rel="stylesheet" href="../../style/index.css">
</head>
<body>
<form action="" method="post">
    <?php
    if (isset($_POST['cancel'])){
        unset($_SESSION['selection']);
        header("Location: ../index.php");
        die();
    }

    if (isset($_POST['dim'])){
        // Seleziona la dimensione del prodotto
        $_SESSION['selection']['product_id'] = $_POST['product'];
        $_SESSION['selection']['qt'] = $_POST['quantity'];

        $name = get_product_name($conn, $_POST['product']);
        $sizes = get_productSizes($conn, $name);
        ?>
        <h1>Seleziona la dimensione - <?php echo $name?></h1>
        <label>
            <select name="size">
                <?php
                foreach ($sizes as $k => $v){
                    try {
                        echo "<option value='" . $v['my_size'] . "'>" . sizeLetter((int)$v['my_size']) . "</option>";
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
                ?>
            </select>
        </label>
        <label>
            Personalizza
            <input type="checkbox" name="personalizza">
        </label>
        <input class="button" type="submit" name="prosegui" value="Prosegui">
        <input class="button" type="submit" name="cancel" value="Annulla">
        <?php
    }else if(isset($_POST['prosegui'])){
        // Fai le aggiunte
        $id = $_SESSION['selection']['product_id'];
        $size = $_POST['size'];
        $_SESSION['selection']['product_id'] = get_sized_product($conn, $id, $size);
        if(isset($_POST['personalizza']) && $_POST['personalizza']){
            ?>
            <h1>Personalizza <?php echo get_product_name($conn, $_SESSION['selection']['product_id'])?></h1>
            <?php
            $ingredients = get_product_ingredients($conn, $_SESSION['selection']['product_id']);
            foreach ($ingredients as $v => $k) {
                ?>
                <label>
                    <?php echo get_ingredient_name($conn, $k['ingredient_id'])?>
                    <input class="button" type="number" name="ing_<?php echo $k['ingredient_id']?>" min="0" value="<?php echo $k['quantity']?>" <?php if($k['strict']) echo " disabled"?>>
                </label>
                <?php
            }
            ?>
                <input class="button" type="submit" name="add" value="Aggiungi">
                <input class="button" type="submit" name="cancel" value="Annulla">
            <?php
        }else{
            foreach ($_POST as $k => $val) {
                if(str_starts_with($k, "ing_")){
                    $_SESSION['selection']['ingredients'][str_replace("ing_", "", $k)] = $val;
                }
            }
            $_SESSION['cart']['products'][] = $_SESSION['selection'];
            unset($_SESSION['selection']);
            header("Location: ../index.php");
            die();
        }
    }else if (isset($_POST['add'])){
        foreach ($_POST as $k => $val) {
            if(str_starts_with($k, "ing_")){
                $_SESSION['selection']['ingredients'][str_replace("ing_", "", $k)] = $val;
            }
        }
        $_SESSION['cart']['products'][] = $_SESSION['selection'];
        unset($_SESSION['selection']);
        header("Location: ../index.php");
        die();
    }else {
        ?>
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
        <label>
            Quantità
            <input type="number" name="quantity" min="1" value="1">
        </label>
        <input class="button" type="submit" name="dim" value="Aggiungi">
        <input class="button" type="submit" name="cancel" value="Annulla">
        <?php
    }
    ?>
</form>
</body>
</html>