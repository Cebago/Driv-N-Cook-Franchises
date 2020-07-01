<?php

require "../conf.inc.php";
require "../functions.php";
session_start();

if (isset($_GET["cart"], $_GET["ingredient"])) {

    $cart = $_GET["cart"];
    $ingredient = $_GET["ingredient"];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE CARTINGREDIENT SET quantity = quantity-1 WHERE cart = :cart AND ingredient = :ingredient");
    $queryPrepared->execute([
        ":cart" => $cart,
        ":ingredient" => $ingredient
    ]);

    $queryPrepared = $pdo->prepare("SELECT price FROM STORE, INGREDIENTS, WAREHOUSES WHERE ingredient = idIngredient AND idIngredient = :ingredient AND warehouse = idWarehouse AND warehouseType = 'Entrepôt'");
    $queryPrepared->execute([
        ":ingredient" => $ingredient
    ]);
    $price = $queryPrepared->fetch(PDO::FETCH_ASSOC);

    $queryPrepared = $pdo->prepare("UPDATE CART SET cartPrice = cartPrice - :price WHERE idCart = :cart");
    $queryPrepared->execute([
        ":price" => $price["price"],
        ":cart" => $cart
    ]);

} else {
    echo "Erreur lors de la modification. Merci de réessayer";
}
