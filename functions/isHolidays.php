<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_GET["truck"])) {
    $truck = $_GET["truck"];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT status FROM TRUCKSTATUS WHERE truck = :truck");
    $queryPrepared->execute([
        ":truck" => $truck
    ]);

    echo json_encode($queryPrepared->fetchAll(PDO::FETCH_ASSOC));

} else {
    echo "Bizarre, un problème est survenu";
}