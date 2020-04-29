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
            $destination = $email;
            $subject = "Activation de votre compte Where2Go";
            $header = "FROM: no-reply-inscription@where2go.fr";
            $link = "https://where2go.fr/isActivated?pseudo=" . urlencode($pseudo) . "&cle=".urlencode($cle);
            $message = 'Bonjour ' . $pseudo . 'Bienvenue sur Where 2 Go, 
            Pour activer votre compte, veuillez cliquer sur le lien ci dessous ou copier/coller dans votre navigateur internet.' . $link . '--------------- Ceci est un mail automatique, Merci de ne pas y répondre.';
            mail($destination, $subject, $message, $header);
            header("HTTP/1.1 200 OK");
            exit;
        }
    }
}
header("HTTP/1.1 401 Unauthorized");
exit;