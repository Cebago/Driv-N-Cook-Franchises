<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isConnected() && isActivated() && isFranchisee()) {
    var_dump($_POST);
    if (isset($_POST["id"], $_POST["user"], $_POST["price"])) {
        $order = $_POST["id"];
        $user = $_POST["user"];
        $price = $_POST["price"];

        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("INSERT INTO TRANSACTION (transactionType, price, user, orders) VALUES ('client', :price, :user, :orders)");
        $queryPrepared->execute([
            ":price" => $price,
            ":user" => $user,
            ":orders" => $order,
        ]);
    }
}