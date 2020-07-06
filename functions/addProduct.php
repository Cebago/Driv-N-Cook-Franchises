<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

$truck = getMyTruck($_SESSION["email"]);

if (isset($_POST["productName"], $_POST["productPrice"], $_POST["productCategory"])) {

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("INSERT INTO PRODUCTS (productName, productPrice, category, truck) VALUES (:name, :price, :category, :truck)");
    $queryPrepared->execute([
        ":name" => $_POST["productName"],
        ":price" => $_POST["productPrice"],
        ":category" => $_POST["productCategory"],
        ":truck" => $truck
    ]);
    header("Location: ../products.php");
} elseif (isset($_POST["productName"], $_POST["productPrice"])) {

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("INSERT INTO PRODUCTS (productName, productPrice, category, truck) VALUES (:name, :price, null, :truck)");
    $queryPrepared->execute([
        ":name" => $_POST["productName"],
        ":price" => $_POST["productPrice"],
        ":truck" => $truck
    ]);
    header("Location: ../products.php");

} else {
    header("Location: ../products.php");
}