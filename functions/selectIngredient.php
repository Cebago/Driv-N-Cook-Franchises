<?php
session_start();
require '../conf.inc.php';
require '../functions.php';
header("Content-type: application/json");
$json = "";

$pdo = connectDB();
$queryPrepared = $pdo->prepare("SELECT ingredientName FROM INGREDIENTS WHERE ingredientCategory = :ingredient");
$queryPrepared->execute([":ingredient" => $_POST["ingredient"]]);
$result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);

