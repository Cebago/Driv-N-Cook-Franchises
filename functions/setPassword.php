<?php
session_start();
require '../conf.inc.php';
require '../functions.php';

if (count($_POST) == 2
    && isset($_POST['newPassword'])
    && isset($_POST['confirm'])) {

    $error = false;
    $listOfErrors = [];
    $email = $_SESSION["email"];

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

    if ($error) {
        $_SESSION["errors"] = $listOfErrors;
        header("Location: ../newPassword.php");
    } else {
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $pdo = connectDB();
        $query = "UPDATE USER SET pwd = :password WHERE emailAddress = :email";
        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute([
            ":password" => $newPassword,
            ":email" => $_SESSION["email"]
        ]);
        header("Location: ../login.php");
    }
} else {
    die ("Tentative de Hack .... !!!!");
}