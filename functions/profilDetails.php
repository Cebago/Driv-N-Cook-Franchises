<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

$email = $_SESSION["email"];

$pdo = connectDB();
$queryPrepared = $pdo->prepare("SELECT idUser, lastname, firstname, emailAddress, phoneNumber, roleName,
                                            DATE_FORMAT(createDate, '%d/%m/%Y') as createDate, address, postalCode, 
                                            city, licenseNumber, fidelityCard
                                            FROM USER, SITEROLE WHERE idRole = USER.userRole AND emailAddress = :email");
$queryPrepared->execute([":email" => $email]);
$result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);