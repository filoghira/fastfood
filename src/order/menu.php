<?php
require "../database/database.php";
require "../utils/utils.php";
session_start();
$conn = connect();
$menu = select_menu($conn);
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
        $_SESSION['selection']['menu_id'] = $_POST['menu'];
        $_SESSION['selection']['qt'] = $_POST['quantity'];

        $name = get_menuName($conn, $_POST['menu']);
        $sizes = get_menuSizes($conn, $name);
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
        <input class="button" type="submit" name="add" value="Aggiungi">
        <input class="button" type="submit" name="cancel" value="Annulla">
        <?php
    }else if (isset($_POST['add'])){
        $_SESSION['cart']['menu'][] = $_SESSION['selection'];
        unset($_SESSION['selection']);
        header("Location: ../index.php");
        die();
    }else {
        ?>
        <label>
            Menù
            <select name="menu">
                <?php
                foreach ($menu as $k=> $v) {
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