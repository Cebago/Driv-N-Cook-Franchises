<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isset($_GET["order"], $_GET["status"])) {
    $order = $_GET["order"];
    $status = $_GET["status"];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE ORDERSTATUS SET status = :status WHERE orders = :order AND status = 27");
    $queryPrepared->execute([
        ":status" => $status,
        ":order" => $order
    ]);


} else {
    echo "Le statut n'a pas pu être modifié !!";
}