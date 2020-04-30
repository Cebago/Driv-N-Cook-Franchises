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
        $queryPrepared = $pdo->prepare("SELECT idUser, lastname, firstname FROM USER WHERE emailAddress = :email");
        $queryPrepared->execute([":email" => $email]);
        $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
        $idUser = $result[0]['idUser'];
        $lastName = $result[0]['lastname'];
        $firstName = $result[0]['firstName'];

        if ($result[0]["idUser"] == $user) {
            $cle = createToken($email);
            $queryPrepared = $pdo->prepare("UPDATE USER SET token = :token WHERE idUser = :user");
            $queryPrepared->execute([
                ":token" => $cle,
                ":user" => $idUser
            ]);
            $destination = $email;
            $subject = "Activation de votre compte Driv'N Cook";
            $header = "FROM: franchises@drivncook.fr";
            $link = "https://franchises.drivncook.fr/isActivated?cle=" . urlencode($cle) . "&id=" . urlencode($idUser) ;
            $message = '
		Bonjour ' . $lastName . ' ' . $firstName . '
		Bienvenue sur Driv\'N Cook,
 
		Pour activer votre compte, veuillez cliquer sur le lien ci-dessous ou le copier/coller dans votre navigateur internet.
 		'.$link.'
 		---------------
 		
 		Ceci est un mail automatique,
 		Merci de ne pas y répondre.';

            $message = wordwrap($message, 70, "\r\n");
            mail($destination, $subject, $message, $header);
            header("HTTP/1.1 200 OK");
            exit;
        }
    }
}
header("HTTP/1.1 401 Unauthorized");
exit;