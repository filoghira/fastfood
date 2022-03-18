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
    <title>FastFood</title>
    <link rel="stylesheet" type="text/css" href="../../style/newProduct.css">
</head>
<body>
<h1>Inserisci un nuovo prodotto</h1>
<form action="newProduct.php" method="post">
    <label for="nome">Nome</label>
    <br>
    <input type="text" name="nome" id="nome" required>
    <br>
    <label for="prezzo">Prezzo</label>
    <br>
    <input type="number" name="prezzo" id="prezzo" min="0.01" required>
    <br>
    <label for="size">Dimensione</label>
    <br>
    <select name="size" id="size">
        <option value="1">Small</option>
        <option value="2">Medium</option>
        <option value="3">Large</option>
    </select>
    <br>
    <label for="categoria">Categoria</label>
    <br>
    <select name="categoria" id="categoria" required>
        <option value="">Seleziona una categoria</option>
        <?php
        $types = select_types($conn);
        echo "fsfsdfsd";
        foreach ($types as $type) {
            echo "<option value='" . $type['id'] . "'>" . $type['name'] . "</option>";
        }
        ?>
    </select>
    <br>
    <input class="button" type="submit" value="Inserisci">
</form>
<?php
if (isset($_POST['nome'], $_POST['prezzo'], $_POST['categoria'], $_POST['size'])) {
    insert_product($conn, $_POST['nome'], $_POST['prezzo'], $_POST['size'], $_POST['categoria']);
    echo "<h4>Prodotto inserito con successo</h4>";
}
?>
<form action="dashboard.php">
    <input class="button" type="submit" value="Torna alla dashboard">
</form>
</body>
</html>

