<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_GET["id"], $_GET["status"])) {
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("DELETE FROM PRODUCTSTATUS WHERE product = :product");
    $queryPrepared->execute([
        ":product" => $_GET["id"]
    ]);
    $queryPrepared = $pdo->prepare("INSERT INTO PRODUCTSTATUS (product, status) VALUES (:product, :status)");
    $queryPrepared->execute([
        ":product" => $_GET["id"],
        ":status" => $_GET["status"]
    ]);
    header("Location: ../products.php");
} else {
    header("Location: ../products.php");
}