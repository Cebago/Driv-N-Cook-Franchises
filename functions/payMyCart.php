<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (isConnected() && isActivated() && isFranchisee()) {
    if (count($_POST) == 5 && isset($_POST["cardNumber"], $_POST["username"],
            $_POST["month"], $_POST["year"], $_POST["ccv"])) {

        $cardNumber = $_POST["cardNumber"];
        $username = trim($_POST["username"]);
        $month = $_POST["month"];
        $year = $_POST["year"];
        $ccv = $_POST["ccv"];

        $error = false;
        $listOfErrors = [];

        if (!preg_match("#[0-9]{16}#", $cardNumber) || strlen($cardNumber) != 16) {
            $error = true;
            $listOfErrors[] = "Votre numéro de carte bleue n'est pas bon";
        }

        if ($month < 1 || $month > 12) {
            $error = true;
            $listOfErrors[] = "Le mois saisi n'est pas bon";
        }

        if ($year < date("y") || $year > date('y') + 5) {
            $error = true;
            $listOfErrors[] = "L'année saisie n'est pas bonne";
        }

        if (!preg_match("#[0-9]{3}#", $ccv) || strlen($ccv) !=3) {
            $error = true;
            $listOfErrors[] = "Le code n'est pas dans un format valide";
        }

        if ($error) {
            $_SESSION["errors"] = $listOfErrors;
            $_SESSION["input"] = $_POST;
            header("Location: ../payment.php");

        } else {

            $pdo = connectDB();

            $queryPrepared = $pdo->prepare("SELECT idTruck, idUser FROM TRUCK, USER WHERE user = idUser AND emailAddress = :email");
            $queryPrepared->execute([":email" => $_SESSION["email"]]);
            $info = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
            $idTruck = $info[0]["idTruck"];
            $idUser = $info[0]["idUser"];

            $cart = lastCart($_SESSION["email"]);

            $queryPrepared = $pdo->prepare("SELECT cartPrice FROM CART WHERE idCart = :cart");
            $queryPrepared->execute([":cart" => $cart]);
            $price = $queryPrepared->fetch(PDO::FETCH_ASSOC);
            $price = $price["cartPrice"];

            $queryPrepared = $pdo->prepare("INSERT INTO ORDERS (orderPrice, orderType, truck, user) VALUES (:orderPrice, 'Commande Franchisé', :truck, :user)");
            $queryPrepared->execute([
                ":orderPrice" => $price,
                ":truck" => $idTruck,
                ":user" => $idUser
            ]);
            $order = $pdo->lastInsertId();
            $queryPrepared = $pdo->prepare("INSERT INTO ORDERSTATUS (orders, status) VALUES (:order, 1)");
            $queryPrepared->execute([
                ":order" => $order
            ]);

            $queryPrepared = $pdo->prepare("INSERT INTO TRANSACTION (transactionType, price, user, orders) 
                                                        VALUES ('buyWarehouse', :price, :user, :order)");
            $queryPrepared->execute([
                ":price" => $price,
                ":user" => $idUser,
                ":order" => $order
            ]);

            $queryPrepared = $pdo->prepare("UPDATE CARTSTATUS SET status = 8 WHERE cart = :cart");
            $queryPrepared->execute([
                ":cart" => $cart
            ]);

            $queryPrepared = $pdo->prepare("SELECT idIngredient FROM INGREDIENTS, CARTINGREDIENT, CART WHERE ingredient = idIngredient AND cart = idCart AND idCart = :cart");
            $queryPrepared->execute([":cart" => $cart]);
            $ingredients = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

            $warehouse = truckWarehouse($idTruck);

            foreach ($ingredients as $ingredient) {
                $queryPrepared = $pdo->prepare("INSERT INTO STORE (warehouse, ingredient, available) VALUES (:warehouse, :ingredient, 1) ON DUPLICATE KEY UPDATE available = 1");
                $queryPrepared->execute([
                    ":warehouse" => $warehouse,
                    ":ingredient" => $ingredient["idIngredient"]
                ]);
            }

            $queryPrepared = $pdo->prepare("INSERT INTO CART (cartPrice, cartType, user) VALUES (0, 'Commande Franchisé', :user)");
            $queryPrepared->execute([":user" => $idUser]);

            $newCart = $pdo->lastInsertId();
            $queryPrepared = $pdo->prepare("INSERT INTO CARTSTATUS (cart, status) VALUES (:cart, 9)");
            $queryPrepared->execute([":cart" => $newCart]);

            header("Location: home.php");
        }

    } else {
        die("Ne pas modifier le formulaire");
    }
} else {
    header("Location: login.php");
}