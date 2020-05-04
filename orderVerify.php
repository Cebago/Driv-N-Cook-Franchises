<?php
session_start();
require "conf.inc.php";
require "functions.php";

    if( count($_POST) == 4
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
    if( !($franchisee != $_SESSION["franchisee"]) ) {
    $error = true;
    $listOfErrors[] = "Merci de ne pas modifier le nom du franchisé";
    }

    //prix
    if($price < 0 || $price > 200){
        $error = true;
        $listOfErrors[] = "Merci de bien vouloir rentrer un prix entre 0 et 200";
    }


    //date : vérification que la date est bonne
    explode('/',$date);
    if(checkdate(($date[0],$date[1],$date[2]) && ($date("Y") > $date[2] < date("Y")))) {
    $error = true;
    $listOfErrors[] = "La date n'est pas bonne, merci de mettre une date valide";
    }

    //fichier

      if( isset($_POST['invoice']) ) // si formulaire soumis
      {
          $content_dir = '/var/www/html/invoices'; // dossier où sera déplacé le fichier

          $tmp_file = $_FILES['fichier']['invoice_test'];
          // on vérifie maintenant l'extension
          $type_file = $_FILES['fichier']['type'];

          if( !is_uploaded_file($tmp_file) || ( !strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'pdf')))
          {
              $error = true;
              $listOfErrors("Le fichier est introuvable et/où n'a pas le bon format(.jpg,.jpef,.bmp,.pdf");
          }


    if($error){
    $_SESSION["errors"] = $listOfErrors;
    $_SESSION["inputErrors"] = $_POST;

    //Rediriger sur orderVerify.php
    header("Location: orderInvoice.php");

    } else {
        $pdo = connectDB();
        $query = "INSERT INTO ORDER (orderPrice, orderDate, orderInvoice) VALUES
    ( :price, :dated, :invoice)";

        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute([
            ":price" => $price,
            ":dated" => $date,
            ":invoice" => $invoice
        ]);

    }

    }
   }


