<?php
session_start();
require "conf.inc.php";
require "functions.php";
if (isConnected() && isActivated() && (isAdmin() || isFranchisee())) {
    if (count($_POST) == 4
        && !empty($_POST["franchisee"])
        && !empty($_POST["price"])
        && !empty($_POST["date"])
        && !empty($_POST["invoice"])
    ) {

        //Nettoyage des chaînes
        $franchisee = $_POST["franchisee"];
        $price = $_POST["price"];
        $date = $_POST["date"];
        $invoice = $_POST["invoice"];

        $error = false;
        $listOfErrors = [];

        //franchisé : vérification qu'il n'y a eu aucune modification
        if (!($franchisee != $_SESSION["franchisee"])) {
            $error = true;
            $listOfErrors[] = "Merci de ne pas modifier le nom du franchisé";
        }

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

        //fichier
        $target_dir = "./invoice";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["invoice"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

            if ($check == false || $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf") {
                $error = true;
                $listOfErrors[] = "Merci de rentrer une image valide de type .jpg / .png / .jpeg / .pdf";
            }
        }


        /*if( isset($_POST['invoice']) ) // si formulaire soumis
        {
            $content_dir = './invoices'; // dossier où sera déplacé le fichier

            $tmp_file = $_FILES['fichier']['invoice_test'];
            // on vérifie maintenant l'extension
            $type_file = $_FILES['fichier']['type'];

            if (!is_uploaded_file($tmp_file) || (!strstr($type_file, 'jpg') || !strstr($type_file, 'jpeg') || !strstr($type_file, 'png') || !strstr($type_file, 'pdf'))) {
                $error = true;
                $listOfErrors("Le fichier est introuvable et/où n'a pas le bon format(.jpg,.jpef,.png,.pdf");
            }
        }*/

        if ($error) {
            $_SESSION["errors"] = $listOfErrors;
            $_SESSION["inputErrors"] = $_POST;

            //Rediriger sur orderInvoice.php
            header("Location: orderInvoice.php");
        } else {
            /* $pdo = connectDB();
             $query = "INSERT INTO ORDER (orderPrice, orderDate, orderInvoice) VALUES
         ( :price, :dated, :invoice)";

             $queryPrepared = $pdo->prepare($query);
             $queryPrepared->execute([
                 ":price" => $price,
                 ":dated" => $date,
                 ":invoice" => __DIR__."/invoices"
             ]);*/
            echo "OK";
        }
    } else {
        echo "KO";
        print_r($_POST);
    }
} else {
    header("Location: login.php");
}