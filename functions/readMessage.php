<?php
session_start();
require "../conf.inc.php";
require "../functions.php";
header("Content-Type: application/json");

$idContact = $_GET["idContact"];

$pdo = connectDB();
$queryPrepared = $pdo->prepare("UPDATE CONTACT SET `isRead`='1' WHERE `idContact`= :idContact");
$queryPrepared->execute([
    ":idContact" => $idContact
]);

