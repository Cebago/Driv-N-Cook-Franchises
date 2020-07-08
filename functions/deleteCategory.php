<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_GET["id"])) {
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("DELETE FROM PRODUCTCATEGORY WHERE idCategory = :id");
    $queryPrepared->execute([
        ":id" => $_GET["id"]
    ]);
    header("Location: ../categories.php");
} else {
    header("Location: ../categories.php");
}