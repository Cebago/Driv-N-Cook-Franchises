<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isset($_GET["truck"], $_GET["status"], $_GET["oldStatus"])) {
    $truck = $_GET["truck"];
    $status = $_GET["status"];
    $old = $_GET["oldStatus"];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE TRUCKSTATUS SET status = :status WHERE truck = :truck AND status = :old");
    $queryPrepared->execute([
        ":status" => $status,
        ":truck" => $truck,
        ":old" => $old
    ]);

} else {
    echo "Bizarre, un problème est survenu";
}