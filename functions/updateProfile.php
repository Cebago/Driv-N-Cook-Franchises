<?php
session_start();
require '../conf.inc.php';
require '../functions.php';

if (count($_POST) == 12
    && !empty($_POST["idUser"])
    && !empty($_POST["lastname"])
    && !empty($_POST["firstname"])
    && !empty($_POST["emailAddress"])
    && !empty($_POST["phoneNumber"])
    && !empty($_POST["roleName"])
    && !empty($_POST["createDate"])
    && !empty($_POST["address"])
    && !empty($_POST["postalCode"])
    && !empty($_POST["city"])
    && !empty($_POST["licenseNumber"])
    && !empty($_POST["fidelityCard"])) {

    $listOfErrors = [];
    $error = false;
    $user = $_POST['idUser'];
    $firstName = htmlspecialchars(ucwords(strtolower(trim($_POST["firstname"]))));
    $lastName = htmlspecialchars(strtoupper(trim($_POST["lastname"])));
    $email = strtolower(trim($_POST["emailAddress"]));
    $phone = $_POST["phoneNumber"];
    $address = htmlspecialchars(ucwords(trim($_POST["address"])));
    $zip = $_POST["postalCode"];
    $city = htmlspecialchars(ucwords(trim($_POST["city"])));
    $license = $_POST["licenseNumber"];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE USER SET firstname = :firstname, lastname = :lastname, phoneNumber = :phoneNumber, address = :address, city = :city, postalCode = :postalCode, licenseNumber = :license WHERE idUser = :id");
    $queryPrepared->execute([
        ":firstname" => $firstName,
        ":lastname" => $lastName,
        ":phoneNumber" => $phone,
        ":address" => $address,
        ":city" => $city,
        ":postalCode" => $zip,
        ":license" => $license
    ]);

} else {
    die ("Tentative de Hack .... !!!!");
}
