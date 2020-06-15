<?php
header("Content-Type: application/json");

require "../conf.inc.php";
require "../functions.php";
session_start();

$queryPrepared = $pdo->prepare("SELECT quantity, idIngredient FROM INGREDIENTS, CARTINGREDIENT, CART, USER 
    WHERE CARTINGREDIENT.ingredient = idIngredient AND CARTINGREDIENT.cart = idCart AND CART.user = idUser AND  user = 1");
$queryPrepared->execute();
$result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

$queryPrepared = $pdo->prepare("SELECT price FROM INGREDIENTS, STORE WHERE ingredient = idIngredient AND idIngredient = :ingredient");
$ingredient = $result["idIngredient"];
$queryPrepared->execute([
    ":ingredient" => $ingredient
]);
$price = $queryPrepared->fetch(PDO::FETCH_ASSOC);
$price = $price["price"];
$finalPrice = $price * $result["quantity"];


echo json_encode($result);