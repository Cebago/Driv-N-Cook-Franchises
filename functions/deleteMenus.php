<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_GET["id"], $_GET["status"])) {
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("DELETE FROM MENUSSTATUS WHERE menus = :menu");
    $queryPrepared->execute([
        ":menu" => $_GET["id"]
    ]);
    $queryPrepared = $pdo->prepare("INSERT INTO MENUSSTATUS (menus, status) VALUES (:menu, :status)");
    $queryPrepared->execute([
        ":menu" => $_GET["id"],
        ":status" => $_GET["status"]
    ]);
    header("Location: ../menus.php");
} else {
    header("Location: ../menus.php");
}