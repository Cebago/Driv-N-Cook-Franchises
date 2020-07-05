<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_GET["order"], $_GET["status"])) {
    $order = $_GET["order"];
    $status = $_GET["status"];

    if ($status == 1) {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("UPDATE ORDERSTATUS SET status = :status WHERE orders = :order AND status = 3");
        $queryPrepared->execute([
            ":status" => $status,
            ":order" => $order
        ]);
    } else {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("UPDATE ORDERSTATUS SET status = :status WHERE orders = :order AND status in (26, 27)");
        $queryPrepared->execute([
            ":status" => $status,
            ":order" => $order
        ]);
    }
} else {
    echo "Le statut n'a pas pu être modifié !!";
}