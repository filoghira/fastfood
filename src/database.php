<?php

function connect(): ?PDO
{
    $host = "localhost";
    $username = "scuola";
    $password = "scuola";
    $database = "fastfood";

    $URI = "mysql:host=$host".";dbname=$database";

    try {
        $conn = new PDO($URI, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return null;
}

function isAdmin($conn, $username): bool
{
    try {
        $stmt = $conn->prepare("SELECT auth_level FROM t_user WHERE username=:username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return (int)($stmt->fetch()['auth_level']) === 1;
    } catch (PDOException $e) {
        echo "Select failed: " . $e->getMessage();
    }
    return false;
}

function insert_product($conn, $name, $price, $size, $category): bool
{
    try {
        $stmt = $conn->prepare("INSERT INTO t_product (name, price, my_size, typeID) VALUES (:name, :price, :size, :type)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':type', $category);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo "Insert failed: " . $e->getMessage();
    }
    return false;
}

function select_products($conn)
{
    try {
        $stmt = $conn->prepare("SELECT id, name FROM t_product GROUP BY name");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Select failed: " . $e->getMessage();
    }
    return null;
}

function select_types($conn) {
    try {
        $stmt = $conn->prepare("SELECT id, name FROM t_type");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Select failed: " . $e->getMessage();
    }
    return null;
}

function select_menu($conn)
{
    try {
        $stmt = $conn->prepare("SELECT id, name FROM t_menu GROUP BY name");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Select failed: " . $e->getMessage();
    }
    return null;
}

function menu_sizes($conn, $menuName)
{
    try {
        $stmt = $conn->prepare("SELECT my_size, price FROM t_menu WHERE name = :menuName");
        $stmt->bindParam(':menuName', $menuName);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Select failed: " . $e->getMessage();
    }
    return null;
}

function get_menu_composition($conn, $menuName) {
    try {
        $stmt = $conn->prepare("select t_product.name, t_product.my_size from r_menu_contains, t_product, t_menu where r_menu_contains.product_id=t_product.id and r_menu_contains.menu_id=t_menu.id and t_menu.name= :menuName group by t_product.name");
        $stmt->bindParam(':menuName', $menuName);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Select failed: " . $e->getMessage();
    }
    return null;
}

function product_sizes($conn, $productName)
{
    try {
        $stmt = $conn->prepare("SELECT my_size, price FROM t_product WHERE name = :prodName");
        $stmt->bindParam(':prodName', $productName);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Select failed: " . $e->getMessage();
    }
    return null;
}

function register($conn, $username, $password)
{
    try {
        $stmt = $conn->prepare("INSERT INTO t_user (username, password_hash) VALUES (:username, :hash)");
        $stmt->bindParam(':username', $username);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':hash', $hash);
        $stmt->execute();
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        echo "Register failed: " . $e->getMessage();
        return null;
    }
}

function select_users($conn)
{
    try {
        $stmt = $conn->prepare("SELECT id, username, auth_level FROM t_user");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Select failed: " . $e->getMessage();
    }
    return null;
}

function get_product_cost($conn, $product_id) {
    try {
        $stmt = $conn->prepare("SELECT price FROM t_product WHERE id = :id");
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result['price'];
    } catch (PDOException $e) {
        echo "Get product cost failed: " . $e->getMessage();
    }
    return null;
}

function order_product($conn, $receipt_id, $product_id, $quantity): bool
{
    try {
        $stmt = $conn->prepare("INSERT INTO r_orderProduct (receipt_id, product_id) VALUES (:rec_id, :prod_id)");
        $stmt->bindParam(':rec_id', $receipt_id);
        $stmt->bindParam(':prod_id', $product_id);
        for($i = 0; $i < $quantity; $i++) {
            $stmt->execute();
        }
        return true;
    } catch (PDOException $e) {
        echo "Order product failed: " . $e->getMessage();
        return false;
    }
}

function order_menu($conn, $receipt_id, $menu_id, $quantity): bool
{
    try {
        $stmt = $conn->prepare("INSERT INTO r_orderMenu (receipt_id, menu_id) VALUES (:rec_id, :menu_id)");
        $stmt->bindParam(':rec_id', $receipt_id);
        $stmt->bindParam(':menu_id', $menu_id);
        for($i = 0; $i < $quantity; $i++) {
            $stmt->execute();
        }
        return true;
    } catch (PDOException $e) {
        echo "Order product failed: " . $e->getMessage();
        return false;
    }
}

function order($conn, $cart, $receipt) {
    foreach ($cart as $elem) {
        if (isset($elem['product_id'])){
            $order = order_product($conn, $receipt, $elem['product_id'], $elem['quantity']);
            add_orderProductIng($conn, $order, $elem['ingredients']);
        } else if (isset($elem['menu_id'])) {
            order_menu($conn, $receipt, $elem['menu_id'], $elem['quantity']);
        }
    }
}

function receipt($conn, $id, $cost): int | bool
{
    try {
        $stmt = $conn->prepare("INSERT INTO t_receipt (emit_date, total, account_id) VALUES (:e_date, :total, :id)");
        $date = date("Y-m-d H:i:s");
        $stmt->bindParam(':e_date', $date);
        $stmt->bindParam(':total', $cost);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $conn->lastInsertId();
    }catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function add_orderProductIng ($conn, $ordProd, $ingredients): bool
{
    try {
        $ingredient = 0;
        $qt = 0;
        $stmt = $conn->prepare("INSERT INTO r_orderProductIng (order_product_id, ingredient_id, quantity) VALUES (:orderProduct_id, :ingredient_id, :qt)");
        $stmt->bindParam(':orderProduct_id', $ordProd);
        $stmt->bindParam(':ingredient_id', $ingredient);
        $stmt->bindParam(':qt', $qt);

        print_r($ingredients);
        foreach ($ingredients as $ingredient => $qt) {
            $stmt->execute();
        }
        return true;
    } catch (PDOException $e) {
        echo "Add order product ingredient failed: " . $e->getMessage();
        return false;
    }
}

function login($conn, $username, $password) {
    try {
        $stmt = $conn->prepare("SELECT id, password_hash FROM t_user WHERE username = :usr");
        $stmt->bindParam(':usr', $username);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        if(password_verify($password, $row['password_hash'])) {
            return $row['id'];
        }

        return null;
    } catch (PDOException $e) {
        echo "Login failed: " . $e->getMessage();
    }
    return null;
}

function get_product_ingredients($conn, $product_id) {
    try {
        $stmt = $conn->prepare("SELECT ingredient_id, quantity, strict FROM r_product_composition WHERE product_id = :prod_id");
        $stmt->bindParam(':prod_id', $product_id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Get product ingredients failed: " . $e->getMessage();
    }
    return null;
}

function get_product_price($conn, $product_id) {
    try {
        $stmt = $conn->prepare("SELECT price FROM t_product WHERE id = :prod_id");
        $stmt->bindParam(':prod_id', $product_id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result['price'];
    } catch (PDOException $e) {
        echo "Get product price failed: " . $e->getMessage();
    }
    return null;
}

function get_ingredient_price($conn, $ingredient_id) {
    try {
        $stmt = $conn->prepare("SELECT price FROM t_ingredient WHERE id = :ing_id");
        $stmt->bindParam(':ing_id', $ingredient_id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result['price'];
    } catch (PDOException $e) {
        echo "Get ingredient price failed: " . $e->getMessage();
    }
    return null;
}

function get_sized_product($conn, $product_id, $product_size) {
    try {
        $stmt = $conn->prepare("SELECT id FROM t_product WHERE id = :prod_id AND my_size = :size");
        $stmt->bindParam(':prod_id', $product_id);
        $stmt->bindParam(':size', $product_size);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch()['id'];
    } catch (PDOException $e) {
        echo "Get sized product failed: " . $e->getMessage();
    }
    return null;
}

function get_product_name ($conn, $product_id) {
    try {
        $stmt = $conn->prepare("SELECT name FROM t_product WHERE id = :id");
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result['name'];
    } catch (PDOException $e) {
        echo "Get product name failed: " . $e->getMessage();
    }
    return null;
}

function get_ingredient_name($conn, $ingredient_id) {
    try {
        $stmt = $conn->prepare("SELECT name FROM t_ingredient WHERE id = :id");
        $stmt->bindParam(':id', $ingredient_id);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result['name'];
    } catch (PDOException $e) {
        echo "Get ingredient name failed: " . $e->getMessage();
    }
    return null;
}