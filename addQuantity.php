<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

$pdo = connectDB();
$cart = lastCart($_SESSION["email"]);

foreach ($_POST as $key => $value) {
    if ($value != 0) {
        //Vérification de l'existence de l'ingrédient dans le panier
        $queryPrepared = $pdo->prepare("SELECT * FROM CARTINGREDIENT WHERE cart = :cart AND ingredient = :ingredient");
        $queryPrepared->execute([
            ":cart" => $cart,
            ":ingredient" => $key
        ]);
        $result = $queryPrepared->fetch();

        // si vide => existe pas
        if (empty($result)) {


            $queryPrepared = $pdo->prepare("INSERT INTO CARTINGREDIENT (cart, ingredient, quantity) VALUES (:cart, :ingredient, :quantity)");
            $queryPrepared->execute([
                ":cart" => $cart,
                ":ingredient" => $key,
                ":quantity" => $value
            ]);

        } else {
            //si pas vide => update

            $queryPrepared = $pdo->prepare("UPDATE CARTINGREDIENT SET quantity = quantity + :quantity WHERE cart = :cart AND ingredient = :ingredient");
            $queryPrepared->execute([
                ":cart" => $cart,
                ":ingredient" => $key,
                ":quantity" => $value
            ]);
        }

        $queryPrepared = $pdo->prepare("SELECT price FROM STORE, INGREDIENTS, WAREHOUSES WHERE idWarehouse = warehouse AND ingredient = idIngredient AND warehouseType = 'Entrepôt' AND idIngredient = :id");
        $queryPrepared->execute([":id" => $key]);
        $price = $queryPrepared->fetch(PDO::FETCH_ASSOC);
        $price = $price["price"];

        $finalPrice = $price * $value;

        $queryPrepared = $pdo->prepare("UPDATE CART SET cartPrice = cartPrice + :price WHERE idCart = :id");
        $queryPrepared->execute([
            ":price" => $finalPrice,
            ":id" => $cart
        ]);
    }
}
header("Location: cart.php");