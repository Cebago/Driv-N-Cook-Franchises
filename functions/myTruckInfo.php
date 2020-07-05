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


$truck = $result["idTruck"];
$queryPrepared = $pdo->prepare("SELECT openDay FROM OPENDAYS WHERE truck = :truck GROUP BY openDay ORDER BY DAYOFWEEK(openDay)");
$queryPrepared->execute([
    ":truck" => $truck
]);
$day = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
$tmp = [];
for ($i = 0; $i < count($day); $i++) {
    $queryPrepared = $pdo->prepare("SELECT openDay, DATE_FORMAT(startHour,'%H:%i') as startHour, DATE_FORMAT(endHour,'%H:%i') as endHour FROM OPENDAYS WHERE truck = :truck AND openDay = :day ORDER BY hour(startHour)");
    $queryPrepared->execute([
        ":day" => $day[$i]["openDay"],
        ":truck" => $truck
    ]);
    $open = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    $tmp = array_merge($tmp, $open);
}
$result["opendays"] = $tmp;

echo json_encode($result);
