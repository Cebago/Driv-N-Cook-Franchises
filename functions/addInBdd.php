<?php
session_start();
require '../conf.inc.php';
require '../functions.php';
var_dump($_POST);

if (isset($_POST, $_POST["category"], $_POST["checkbox"], $_POST["newIngredient"], $_POST["newIngredientEN"], $_POST["newIngredientES"])) {
    //ajout dans ingredients et dans cart + img

    $ingredient = htmlspecialchars(ucwords(trim($_POST["newIngredient"])));
    $category = htmlspecialchars(ucwords(trim($_POST["category"])));
    $error = false;
    $listOfErrors = [];

    if (!preg_match('#[a-zA-Z]*#', $ingredient)) {
        $error = true;
        $listOfErrors[] = "Le nom d'ingrédient n'est pas valide";
    }

    if (strlen($ingredient) <= 3 && strlen($ingredient) > 15) {
        $error = true;
        $listOfErrors[] = "Le nom de d'ingrédient doit être compris entre 3 et 15 caractères";
    }

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idIngredient FROM INGREDIENTS WHERE ingredientCategory = :category");
    $queryPrepared->execute([
        ":category" => $category
    ]);
    $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if (empty($result)) {
        $error = true;
        $listOfErrors[] = "Merci de ne pas modifier la catégorie";
    }

    $queryPrepared = $pdo->prepare("SELECT idIngredient FROM INGREDIENTS WHERE ingredientName = :name");
    $queryPrepared->execute([
        ":name" => $ingredient
    ]);
    $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if (!empty($result)) {
        $error = true;
        $listOfErrors[] = "Merci de ne pas ajouter deux fois le même ingrédient";
    }

    if ($error) {
        $_SESSION["errors"] = $listOfErrors;
        header("Location: ../ingredientTruck.php");
    } else {
        $target_dir = "ingredientImg/";
        $target_file = $target_dir . basename($_FILES["ingredientImg"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadDir = $target_dir . $ingredient . "." . $imageFileType;
        if (isset($_POST["ingredientImg"])) {
            $check = getimagesize($_FILES["ingredientImg"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $error = true;
                $listOfErrors[] = "File is not an image.";
                $uploadOk = 0;
            }
        }
        if (file_exists($target_file)) {
            $error = true;
            $listOfErrors[] = "Sorry, file already exists.";
            $uploadOk = 0;
        }
        if ($_FILES["ingredientImg"]["size"] > 200000000) {
            $error = true;
            $listOfErrors[] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $error = true;
            $listOfErrors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            $listOfErrors[] = "Sorry, your file was not uploaded.";
            $error = true;
        } elseif ($error) {
            $_SESSION["errors"] = $listOfErrors;
            header("Location: ../ingredientTruck.php");
        } else {

            if (move_uploaded_file($_FILES["ingredientImg"]["tmp_name"], "../" . $uploadDir)) {
                $pdo = connectDB();
                $queryPrepared = $pdo->prepare("INSERT INTO INGREDIENTS (ingredientName, ingredientCategory, ingredientImage) VALUES (:name, :category, :image)");
                $queryPrepared->execute([
                    ":name" => $ingredient,
                    ":category" => $category,
                    ":image" => $uploadDir
                ]);
                $id = $pdo->lastInsertId();
                $queryPrepared = $pdo->prepare("SELECT warehouse FROM TRUCKWAREHOUSE, TRUCK, USER, WAREHOUSES WHERE user = idUser AND truck = idTruck AND emailAddress = :email AND warehouseType = 'Camion' AND warehouse = idWarehouse");
                $queryPrepared->execute([
                    ":email" => $_SESSION["email"]
                ]);
                $idWarehouse = $queryPrepared->fetch(PDO::FETCH_ASSOC);
                $idWarehouse = $idWarehouse["warehouse"];
                $queryPrepared = $pdo->prepare("INSERT INTO STORE (warehouse, ingredient, available) VALUES (:warehouse, :ingredient, 1)");
                $queryPrepared->execute([
                    ":warehouse" => $idWarehouse,
                    ":ingredient" => $id
                ]);
                //ajout des traductions dans le fichier json

                $jsonFilePath = '../../Driv-N-Cook-Client/assets/traduction.json';
                $jsonFile = file_get_contents($jsonFilePath);
                $jsonFile = json_decode($jsonFile, true);

                $jsonFile["ingredients"] = array($_POST["newIngredient"] => array("en_EN" => $_POST["newIngredientEN"], "es_ES" => $_POST["newIngredientES"]));

                $newJsonString = json_encode($jsonFile);
                file_put_contents($jsonFilePath, $newJsonString);


            } else {
                $listOfErrors[] = "Sorry, there was an error uploading your file.";
                $_SESSION["errors"] = $listOfErrors;
            }
        }
    }


} else if (isset($_POST, $_POST["category"], $_POST["ingredient"])) {
    //ajout au cart
    $ingredient = htmlspecialchars(ucwords(trim($_POST["ingredient"])));
    $category = htmlspecialchars(ucwords(trim($_POST["category"])));
    $error = false;
    $listOfErrors = [];

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idIngredient FROM INGREDIENTS WHERE ingredientCategory = :category");
    $queryPrepared->execute([
        ":category" => $category
    ]);
    $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if (empty($result)) {
        $error = true;
        $listOfErrors[] = "Merci de ne pas modifier la catégorie";
    }

    $queryPrepared = $pdo->prepare("SELECT idIngredient FROM INGREDIENTS WHERE ingredientName = :name");
    $queryPrepared->execute([
        ":name" => $ingredient
    ]);
    $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if (empty($result)) {
        $error = true;
        $listOfErrors[] = "Merci de ne pas modifier la liste d'ingrédients";
    }

    if ($error) {
        $_SESSION["errors"] = $listOfErrors;
        header("Location: ../ingredientTruck.php");
    } else {
        $ingredient = $result["idIngredient"];
        $queryPrepared = $pdo->prepare("SELECT warehouse FROM TRUCKWAREHOUSE, TRUCK, USER, WAREHOUSES WHERE user = idUser AND truck = idTruck AND emailAddress = :email AND warehouseType = 'Camion' AND warehouse = idWarehouse");
        $queryPrepared->execute([
            ":email" => $_SESSION["email"]
        ]);
        $idWarehouse = $queryPrepared->fetch(PDO::FETCH_ASSOC);
        $idWarehouse = $idWarehouse["warehouse"];
        $queryPrepared = $pdo->prepare("INSERT INTO STORE (warehouse, ingredient, available) VALUES (:warehouse, :ingredient, 1)");
        $queryPrepared->execute([
            ":warehouse" => $idWarehouse,
            ":ingredient" => $ingredient
        ]);
    }
}

$last = isset($_POST["lastOne"]);

if ($last == 1) {
    header("Location: ../orderInvoice.php");
} else {
    header("Location: ../ingredientTruck.php");
}
