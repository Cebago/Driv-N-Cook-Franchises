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
    if ($ingredient == $_POST["ingredient"]) {
        $listOfErrors = "Merci de ne pas ajouter 2 fois le même ingrédient";
    }

    if ($error) {
        print_r($listOfErrors);
    } else {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("INSERT INTO ingredient VALUES ingredientName = :ingredientName, ingredientCategory = :ingredientCategory, ingredientImage = :ingredientImage");
        $queryPrepared->execute([":ingredient" => $ingredient]);
    }
}
