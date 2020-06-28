<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

$pdo = connectDB();
$queryPrepared = $pdo->prepare("SELECT idCart FROM CART, USER WHERE idUser = user AND emailAddress = :user ORDER BY idCart DESC LIMIT 1");
$queryPrepared->execute([
    ":user" => $_SESSION["email"]
]);
$cart = $queryPrepared->fetch(PDO::FETCH_ASSOC);
$cart = $cart["idCart"];

foreach ($_POST as $key => $value) {
    echo "id = " . $key;
    echo "valeur = " . $value;
    if ($value != 0) {
        //Vérification de l'existence de l'ingrédient dans le panier
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT * FROM CARTINGREDIENT WHERE cart = :cart AND ingredient = :ingredient");
        $queryPrepared->execute([
            ":cart" => $cart,
            ":ingredient" => $key
        ]);
        $result = $queryPrepared->fetch();

        // si vide => existe pas
        if (empty($result)) {

            $pdo = connectDB();
            $queryPrepared = $pdo->prepare("INSERT INTO CARTINGREDIENT (cart, ingredient, quantity) VALUES (:cart, :ingredient, :quantity)");
            $queryPrepared->execute([
                ":cart" => $cart,
                ":ingredient" => $key,
                ":quantity" => $value
            ]);
        } else {
            //si pas vide => update
            $pdo = connectDB();
            $queryPrepared = $pdo->prepare("UPDATE CARTINGREDIENT SET quantity = quantity + :quantity WHERE cart = :cart AND ingredient = :ingredient");
            $queryPrepared->execute([
                ":cart" => $cart,
                ":ingredient" => $key,
                ":quantity" => $value
            ]);
        }
    }
}
header("Location: cart.php");