<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_GET["truck"], $_GET["lng"], $_GET["lat"])) {
    $idTruck = $_GET["truck"];
    $lng = $_GET["lng"];
    $lat = $_GET["lat"];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("REPLACE INTO LOCATION (lat, lng, truck) VALUES (:lat, :lng, :idTruck);");
    $queryPrepared->execute([
        ":lat" => $lat,
        ":lng" => $lng,
        ":idTruck" => $idTruck
    ]);

}