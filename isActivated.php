<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isset($_GET, $_GET['id'], $_GET['cle']) && count($_GET) == 2) {
    $cle = $_GET['cle'];
    $id = $_GET['id'];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idUser, emailAddress FROM USER, USERTOKEN WHERE idUser = user AND idUser = :id 
                                     AND token = :token AND tokenType = 'Site'");
    $queryPrepared->execute([
        ":id" => $id,
        ":token" => $cle
    ]);

    $user = $queryPrepared->fetch(PDO::FETCH_ASSOC);

    if (!empty($user)) {
        $queryPrepared = $pdo->prepare("UPDATE USER SET isActivated = true WHERE idUser = :user");
        $queryPrepared->execute([
            ":user" => $user['idUser']
        ]);
        $_SESSION["email"] = $user["emailAddress"];
        header("Location: newPassword.php");
    } else {
        header("Location: login.php");
    }
} else {
    header("Location: login.php");
}
