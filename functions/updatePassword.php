<?php
session_start();
require '../conf.inc.php';
require '../functions.php';

if (count($_POST) == 3
    && isset($_POST['oldPassword'])
    && isset($_POST['newPassword'])
    && isset($_POST['confirm'])) {

    $error = false;
    $listOfErrors = [];
    $email = $_SESSION["email"];
    $token = $_SESSION["token"];

    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];
    $confirm = $_POST["confirm"];

    if (strlen($newPassword) < 8 || strlen($newPassword) > 30
        || !preg_match("#[a-z]#", $newPassword)
        || !preg_match("#[A-Z]#", $newPassword)
        || !preg_match("#\d#", $newPassword)) {

        $error = true;
        $listOfErrors[] = "Le mot de passe doit faire entre 8 et 30 caractères avec des minuscules, majuscules et chiffres";
    }


    if ($confirm != $newPassword) {
        $error = true;
        $listOfErrors[] = "Le mot de passe de confirmation ne correspond pas";
    }

    $pdo = connectDB();
    $query = "SELECT pwd FROM USER WHERE emailAddress = :email";
    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute([":email" => $email]);
    $actual = $queryPrepared->fetch(PDO::FETCH_ASSOC);


    if (!password_verify($oldPassword, $actual['pwd'])) {
        $error = true;
        $listOfErrors[] = "Votre mot de passe actuel ne correspond pas";
    }


    if ($error) {
        $_SESSION["errors"] = $listOfErrors;
        header("Location: ../myPassword.php");

    } else {

        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $pdo = connectDB();
        $query = "UPDATE USER SET pwd = :password WHERE emailAddress = :email";

        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute([
            ":password" => $newPassword,
            ":email" => $email
        ]);
        header("Location: ../myPassword.php");
    }
} else {
    die ("Tentative de Hack .... !!!!");
}