<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

$truck = getMyTruck($_SESSION["email"]);

if (isset($_POST["menuName"], $_POST["menuPrice"], $_POST["products"])) {

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("INSERT INTO MENUS (menuName, menuPrice, truck) VALUES (:name, :price, :truck)");
    $queryPrepared->execute([
        ":name" => $_POST["menuName"],
        ":price" => $_POST["menuPrice"],
        ":truck" => $truck
    ]);

    $menus = $pdo->lastInsertId();

    $queryPrepared = $pdo->prepare("INSERT INTO MENUSSTATUS (menus, status) VALUES (:menus, 22)");
    $queryPrepared->execute([":menus" => $menus]);


    $products = $_POST["products"];
    foreach ($products as $product) {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("INSERT INTO SOLDIN (menu, product) VALUES (:menu, :product)");
        $queryPrepared->execute([
            ":menu" => $menus,
            ":product" => $product
        ]);
    }

    header("Location: ../menus.php");
} else {
    header("Location: ../menus.php?error=true");
}