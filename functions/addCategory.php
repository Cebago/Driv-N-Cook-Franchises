<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_POST["categoryName"])) {

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("INSERT INTO PRODUCTCATEGORY (categoryName) VALUE (:cat)");
    $queryPrepared->execute([
        ":cat" => $_POST["categoryName"]
    ]);
    header("Location: ../categories.php");
} else {
    header("Location: ../categories.php");
}