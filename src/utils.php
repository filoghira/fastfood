<?php
function calcola_aggiunte($conn, $ingredients, $prod_id): float|int
{
    $original = get_product_ingredients($conn, $prod_id);

    $temp = array();
    foreach ($original as $ing) {
        if(!$ing['strict']) {
            $temp[$ing['ingredient_id']] = $ing['quantity'];
        }
    }

    $aggiunte = 0;
    foreach($ingredients as $ingredient => $qt){
        $aggiunte += ($qt - $temp[$ingredient]) * get_ingredient_price($conn, $ingredient);
    }
    return $aggiunte;
}

function sizeLetter($size): string
{
    return match ($size) {
        1 => "Piccolo",
        2 => "Medio",
        3 => "Grande",
        default => throw new Exception("Errore nella selezione del prodotto"),
    };
}

function get_updatedIngredients($original, $ingredients)
{
    $temp = array();
    foreach ($original as $ing) {
        $temp[$ing['ingredient_id']] = $ing['quantity'];
    }

    foreach($ingredients as $ingredient => $qt){
        $temp[$ingredient] = $qt;
    }
    return $temp;
}