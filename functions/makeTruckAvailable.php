<?php
session_start();
require "../conf.inc.php";
require "../functions.php";
if (isset($_GET["truck"])) {
    $truck = $_GET["truck"];
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("DELETE FROM TRUCKSTATUS WHERE status = 10 AND truck = :truck");
    $queryPrepared->execute([
        ":truck" => $truck
    ]);
}
header("Location: ../truckMaintenance.php");

