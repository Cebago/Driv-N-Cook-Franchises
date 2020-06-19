<?php

require "../conf.inc.php";
require "../functions.php";
session_start();

if (isset($_GET["cart"], $_GET["ingredient"])){

    $cart = $_GET["cart"];
    $ingredient = $_GET["ingredient"];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE CARTINGREDIENT SET quantity = quantity+1 WHERE cart = :cart AND ingredient = :ingredient");
    $queryPrepared->execute([
        ":cart" => $cart,
        ":ingredient" => $ingredient
    ]);

}else{
    echo "Erreur lors de la modification. Merci de réessayer";
}
