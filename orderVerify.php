<?php
session_start();
require "conf.inc.php";
require "functions.php";


if (count($_POST) == 2
    && !empty($_POST["price"])
    && !empty($_POST["date"])
) {

    //Nettoyage des chaînes
    $price = $_POST["price"];
    $date = $_POST["date"];

    $error = false;
    $listOfErrors = [];

    //prix
    if ($price < 0 || $price > 1000) {
        $error = true;
        $listOfErrors[] = "Merci de bien vouloir rentrer un prix entre 0 et 1000";
    }

    //date : vérification que la date est bonne
    explode('/', $date);
    if (checkdate($date[0], $date[1], $date[2]) && (date("Y") == $date[2])) {
        $error = true;
        $listOfErrors[] = "La date n'est pas bonne, merci de mettre une date valide";
    }


    if ($error) {
        $_SESSION["errors"] = $listOfErrors;
        $_SESSION["inputErrors"] = $_POST;

        //Rediriger sur orderInvoice.php
        header("Location: orderInvoice.php");
    } else {

        $title = $date;
        $target_dir = "../invoices/";
        $uploadOk = 1;
        $target_file = $target_dir . basename($_FILES["invoice"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (isset($_POST["submit"])) {
            $check = filesize($_FILES["invoice"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $listOfErrors[] = "Ce n'est pas un fichier !";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_dir . $title . $imageFileType)) {
            echo "Votre fichier existe déjà";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["invoice"]["size"] > 5000000) {
            $listOfErrors[] = "Votre fichier est trop gros";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "pdf" || $imageFileType != "png" || $imageFileType != "jpg" || $imageFileType != "jpeg") {
            $listOfErrors[] = "Seules les extensions .pdf, .png, . jpg et .jpeg sont acceptés";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Desolé votre fichier n'est pas envoyé";
        } else {
            if (move_uploaded_file($_FILES["invoice"]["tmp_name"], "../invoices/" . $title . "." . $imageFileType)) {
                $directory = "../invoices/" . $title . "." . $imageFileType;
                $uploadOk = true;
            } else {
                $uploadOk = false;
                $listOfErrors[] = "Une erreur a été rencontrée lors du téléchargement";
            }
        }
        if ($uploadOk) {
            $pdo = connectDB();

            $queryPrepared = $pdo->prepare("SELECT idUser, idTruck FROM USER, TRUCK WHERE emailAddress = :email AND user = idUser");
            $queryPrepared->execute([
                ":email" => $_SESSION["email"]
            ]);
            $info = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
            $user = $info[0]["idUser"];
            $truck = $info[0]["idTruck"];

            $query = "INSERT INTO ORDERS (orderPrice, orderDate, orderInvoice, orderType, user, truck) VALUES
                    ( :price, :dated, :invoice, 'Commande Franchisé', :user, :truck)";

            $queryPrepared = $pdo->prepare($query);
            $queryPrepared->execute([
                ":price" => $price,
                ":dated" => $date,
                ":invoice" => $directory,
                ":user" => $user,
                ":truck" => $truck
            ]);
            $id = $pdo->lastInsertId();

            $queryPrepared = $pdo->prepare("INSERT INTO TRANSACTION (transactionType, price, user, orders) VALUES ('buyExtern', :price, :user, :order)");
            $queryPrepared->execute([
                ":price" => $price,
                ":user" => $user,
                ":order" => $id
            ]);

            $queryPrepared = $pdo->prepare("INSERT INTO ORDERSTATUS (orders, status) VALUES (:order, :status)");
            $queryPrepared->execute([
                ":order" => $id,
                ":status" => 4
            ]);

            header("Location: home.php");

        } else {
            $_SESSION["errors"] = $listOfErrors;
            $_SESSION["inputErrors"] = $_POST;

            //Rediriger sur orderInvoice.php
            header("Location: orderInvoice.php");
        }
    }
} else {
    die("Ne pas modifier le formulaire !!!");
}



