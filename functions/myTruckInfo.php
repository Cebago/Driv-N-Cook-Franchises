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
$queryPrepared = $pdo->prepare("SELECT openDay FROM pa2a2drivncook.OPENDAYS WHERE truck = 1 GROUP BY openDay ORDER BY DAYOFWEEK(openDay)");
$truck = $result["idTruck"];
$queryPrepared->execute([
    ":truck" => $truck
]);
$day = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
$tmp = [];
for ($i = 0; $i < count($day); $i++) {
    $queryPrepared = $pdo->prepare("SELECT op1.idOpen, op1.openDay, op1.startHour as morningOpen, op1.endHour morningClose, op2.startHour lunchOpen, op2.endHour lunchClose FROM OPENDAYS as op1 
    LEFT  JOIN OPENDAYS as op2 ON op1.openDay = op2.openDay 
    WHERE op1.openDay = :day 
      AND op1.truck = :truck 
      AND op1.startHour != op2.startHour 
      AND op1.endHour != op2.endHour 
      AND op1.startHour < op2.startHour
    ORDER BY op1.startHour");
    $queryPrepared->execute([
        ":day" => $day[$i]["openDay"] ,
        ":truck" => $truck
    ]);
    $open = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    $tmp = array_merge($tmp, $open);
}
$result["opendays"] = $tmp;

echo json_encode($result);
