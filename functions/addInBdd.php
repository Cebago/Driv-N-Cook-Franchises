<?php
session_start();
require '../conf.inc.php';
require '../functions.php';

if(isset($_POST) && $_POST["ingredient"] && $_POST["category"]) {
    $ingredient = $_POST["ingredient"];
    $ingredient = htmlspecialchars(ucwords(trim($ingredient)));
    $category = $_POST["category"];
    $category = htmlspecialchars(ucwords(trim($category)));
    $error = FALSE;
    $listOfErrors = "";

    if (!preg_match("#[a-zA-Z]*#", $ingredient) || !preg_match("#[a-zA-Z]*#", $category)) {
        $error = TRUE;
        $listOfErrors .= "Merci de ne pas modifier la page";
    }

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idIngredient FROM INGREDIENTS WHERE ingredientName = :name");
    $queryPrepared->execute([
        ":name" => $ingredient
    ]);
    $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if(!empty($result)){
        $error = true;
        $listOfErrors .= "Merci de ne pas ajouter 2 fois le même ingrédient";
    }

    if ($error) {
        print_r($listOfErrors);
    } else {
        $queryPrepared = $pdo->prepare("INSERT INTO ingredients VALUES ingredientName = :ingredientName, ingredientCategory = :ingredientCategory");
        $queryPrepared->execute([
            ":ingredient" => $ingredient,
            ":ingredientCategory" => $category

        ]);
    }
}
