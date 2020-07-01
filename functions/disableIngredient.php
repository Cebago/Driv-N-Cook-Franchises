<?php
session_start();
require '../conf.inc.php';
require '../functions.php';

if (isset($_POST) && $_POST["ingredient"]) {
    $ingredient = $_POST["ingredient"];
    $error = FALSE;
    $listOfErrors = "";

    if (!preg_match("#\d*#", $ingredient)) {
        $error = TRUE;
        $listOfErrors = "Merci de ne pas modifier la page";
    }

    if ($error) {
        print_r($listOfErrors);
    } else {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("UPDATE STORE SET available = FALSE WHERE ingredient = :ingredient");
        $queryPrepared->execute([":ingredient" => $ingredient]);
    }
}






