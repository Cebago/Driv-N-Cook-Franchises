<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_GET["id"])) {
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("DELETE FROM PRODUCTS WHERE idProduct = :id");
    $queryPrepared->execute([
        ":id" => $_GET["id"]
    ]);
    header("Location: ../products.php");
} else {
    header("Location: ../products.php");
}