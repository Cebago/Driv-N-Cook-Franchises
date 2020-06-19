<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

echo "<pre>";
print_r($_POST);
echo "</pre>";

foreach ($_POST as $key => $value) {
    echo "id = " . $key;
    echo "valeur = " . $value;
    if ($value != 0) {
        //Vérification de l'existence de l'ingrédient dans le panier
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT * FROM CARTINGREDIENT WHERE cart = :cart AND ingredient = :ingredient");
        $queryPrepared->execute([
            ":cart" => 1,
            ":ingredient" => $key
        ]);
        $result = $queryPrepared->fetch();

        // si vide => existe pas
        if (empty($result)) {

            $pdo = connectDB();
            $queryPrepared = $pdo->prepare("INSERT INTO CARTINGREDIENT (cart, ingredient, quantity) VALUES (:cart, :ingredient, :quantity)");
            $queryPrepared->execute([
                ":cart" => 1,
                ":ingredient" => $key,
                ":quantity" => $value
            ]);
        } else {
            //si pas vide => update
            $pdo = connectDB();
            $queryPrepared = $pdo->prepare("UPDATE CARTINGREDIENT SET quantity = quantity + :quantity WHERE cart = :cart AND ingredient = :ingredient");
            $queryPrepared->execute([
                ":cart" => 1,
                ":ingredient" => $key,
                ":quantity" => $value
            ]);
        }
    }
}
header("Location: cart.php");