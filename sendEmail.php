<?php
require 'conf.inc.php';
require 'functions.php';


if (count($_POST) == 3
    && isset($_POST['server'])
    && isset($_POST['user'])
    && isset($_POST['email'])) {

    $server = htmlspecialchars(trim($_POST['server']));
    $user = $_POST['user'];
    $email = strtolower(trim($_POST['email']));

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && $server == "dc4b1b8d545be57b90e18bce49010af7") {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT idUser, lastname, firstname FROM USER WHERE emailAddress = :email");
        $queryPrepared->execute([":email" => $email]);
        $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
        $idUser = $result[0]['idUser'];
        $lastName = $result[0]['lastname'];
        $firstName = $result[0]['firstname'];

        if ($result[0]["idUser"] == $user) {
            $cle = createToken($email);
            $queryPrepared = $pdo->prepare("UPDATE USER, USERTOKEN SET token = :token WHERE idUser = :user AND user = idUser AND tokenType = 'Site' ");
            $queryPrepared->execute([
                ":token" => $cle,
                ":user" => $idUser
            ]);

            $queryPrepared = $pdo->prepare("INSERT INTO USERTOKEN (token, tokenType, user) VALUES (:token, 'Site', :id)");
            $queryPrepared->execute([
                ":token" => $cle,
                ":id" => $idUser
            ]);

            $queryPrepared = $pdo->prepare("INSERT INTO USERTOKEN (tokenType, user) VALUES ('Appli', :id)");
            $queryPrepared->execute([
                ":id" => $idUser
            ]);

            $destination = $email;
            $admin = ($_SERVER["SERVER_ADMIN"]);
            $domaineAddresse = substr($admin, strpos($admin, '@') + 1, strlen($admin));
            $subject = "Activation de votre compte Driv'N Cook";
            $header = "From: noreply@" . $domaineAddresse . "\n";
            $header .= "X-Sender: <noreply@drivncook.fr>\n";
            $header .= "X-Mailer: PHP\n";
            $header .= "Return-Path: <noreply@drivncook.fr>\n";
            $header .= "Content-Type: text/html; charset=iso-8859-1\n";
            $link = "https://franchises.drivncook.fr/isActivated.php?cle=" . urlencode($cle) . "&id=" . urlencode($idUser);

            $html = file_get_contents('mail.html');
            $html = str_replace("{{firstname}}", $firstName . " !", $html);
            $html = str_replace("{{link}}", $link, $html);
            mail($destination, $subject, $html, $header);
            header("HTTP/1.1 200 OK");
            exit;
        }
    }
}
header("HTTP/1.1 401 Unauthorized");
exit;
