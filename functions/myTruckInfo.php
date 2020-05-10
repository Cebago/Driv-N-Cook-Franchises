<?php
require "../conf.inc.php";
require "../functions.php";
header("Content-Type: application/json");

$user = $_GET["user"];

$pdo = connectDB();
$queryPrepared = $pdo->prepare("SELECT idTruck, truckName, truckManufacturers, truckModel, licensePlate, km FROM TRUCK WHERE user = :user");
$queryPrepared->execute([
    ":user" => $user
]);
$result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
$queryPrepared = $pdo->prepare("SELECT openDay as weekDay, DATE_FORMAT(startHour, '%H:%i') as startHour, DATE_FORMAT(endHour, '%H:%i') as endHour FROM OPENDAYS WHERE truck = :truck");
$truck = $result["idTruck"];
$queryPrepared->execute([
    ":truck" => $truck
]);
$open = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
$result["opendays"] = $open;

echo json_encode($result);
