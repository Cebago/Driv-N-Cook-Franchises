<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

$truck = getMyTruck($_SESSION["email"]);

if (isset($_POST["productName"], $_POST["productPrice"], $_POST["productCategory"], $_POST["ingredients"])) {

    if ($_POST["productCategory"] == "") {
        $category = null;
    } else {
        $category = $_POST["productCategory"];
    }

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("INSERT INTO PRODUCTS (productName, productPrice, category, truck) VALUES (:name, :price, :category, :truck)");
    $queryPrepared->execute([
        ":name" => $_POST["productName"],
        ":price" => $_POST["productPrice"],
        ":category" => $category,
        ":truck" => $truck
    ]);

    $product = $pdo->lastInsertId();

    $queryPrepared = $pdo->prepare("INSERT INTO PRODUCTSTATUS (product, status) VALUES (:product, 19)");
    $queryPrepared->execute([":product" => $product]);


    $ingredients = $_POST["ingredients"];
    foreach ($ingredients as $ingredient) {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("INSERT INTO COMPOSE (ingredient, product) VALUES (:ingredient, :product)");
        $queryPrepared->execute([
            ":ingredient" => $ingredient,
            ":product" => $product
        ]);
    }

    header("Location: ../products.php");
} else {
    header("Location: ../products.php?error=true");
}