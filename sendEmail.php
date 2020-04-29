<?php
require 'conf.inc.php';
require 'functions.php';


if (count($_POST) == 3
    && isset($_POST['server'])
    && isset($_POST['user'])
    && isset($_POST['email']) ) {

    $server = htmlspecialchars(strtoupper(trim($_POST['server'])));
    $user = $_POST['user'];
    $email = strtolower(trim($_POST['email']));

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && $server == "coucou") {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT idUser FROM USER WHERE emailAddress = :email");
        $queryPrepared->execute([":email" => $email]);
        $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

        if ($result[0]["idUser"] == $user) {
            $queryPrepared = $pdo->prepare("UPDATE USER SET isActivated = true WHERE idUser = :user");
            $queryPrepared->execute([":user" => $user]);
            header("HTTP/1.1 200 OK");
            exit;
        }
    }
}
header("HTTP/1.1 401 Unauthorized");
exit;