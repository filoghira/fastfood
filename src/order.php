<?php
    require "database.php";
    session_start();

    if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        header("Location: login.php");
    }

    function getSize($size): int
    {
        return match ($size) {
            "S" => 1,
            "M" => 2,
            "L" => 3,
            default => throw new Exception("Errore nella selezione del prodotto"),
        };
    }

    $servername = "localhost";
    $username = "scuola";
    $password = "scuola";
    $database = "fastfood";
    $conn = connect();
    $cost = 0;
    unset($_SESSION['cart']);

    if (isset($_POST['order'])){
        foreach ($_POST as $key => $value) {
            if ($value > 0 && str_starts_with($key, "prod_")){
                $_SESSION['cart']['products'][str_replace("prod_", "", $key)] = 0;
            }else if (str_starts_with($key, "p_size_")){
                $id = str_replace("p_size_", "", $key);
                if (isset($_SESSION['cart']['products'][$id])) {
                    try {
                        $_SESSION['cart']['products'][$id] = getSize($value);
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }else if ($value > 0 && str_starts_with($key, 'menu_')){
                $_SESSION['cart']['menu'][str_replace("menu_", "", $key)] = 0;
            }else  if (str_starts_with($key, "m_size_")){
                $id = str_replace("m_size_", "", $key);
                if (isset($_SESSION['cart']['menu'][$id])) {
                    try {
                        $_SESSION['cart']['menu'][$id] = getSize($value);
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }
        }
        $cost = order($conn, $_SESSION['cart'], $_SESSION['loggedin']);
    }
?>
<html>
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
