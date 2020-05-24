<?php
session_start();
require '../conf.inc.php';
require '../functions.php';

if (isset($_POST)
    && (count($_POST) == 4
        || count($_POST) == 5 )
) {
    $name = ucfirst(trim(htmlspecialchars($_POST["incidentName"])));
    $date = $_POST["incidentDate"];
    $km = $_POST["incidentKM"];
    $price = $_POST["incidentPrice"];
    $switch = false;
    if (isset($_POST["petrificusTotalus"]) && $_POST["petrificusTotalus"] == "on") {
        $switch = true;
    }

    $error = false;
    $listOfErrors = [];


    if (!preg_match("#\d*#", $price) || $price <= 0) {
        $error = true;
        $listOfErrors[] = "Le prix saisi n'est pas un nombre valide";
    }

    if (!preg_match("#\d*#", $km) || $km <= 0) {
        $error = true;
        $listOfErrors[] = "Le kilométrage saisi n'est pas un nombre valide";
    }
    $newDate = (getdate(strtotime($date)));
    $day = $newDate["mday"];
    $month = $newDate["mon"];
    $year = $newDate["year"];
    if (!checkdate($month, $day, $year)) {
        $error = true;
        $listOfErrors[] = "La date saisie n'est pas correcte";
    }

    if ($error) {
        $_SESSION["errors"] = $listOfErrors;
        header("Location: ../truckMaintenance.php");
    } else {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT idTruck FROM TRUCK, USER WHERE user = USER.idUser AND USER.emailAddress = :email");
        $emailAddress = $_SESSION["email"];
        $queryPrepared->execute([
            ":email" => $emailAddress
        ]);
        $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
        $truck = $result["idTruck"];
        $truck = 1;
        $queryPrepared = $pdo->prepare("INSERT INTO MAINTENANCE (maintenanceName, maintenancePrice, maintenanceDate, km, truck) VALUES (:name, :price, :date, :km, :truck)");
        $queryPrepared->execute([
            ":name" => $name,
            ":price" => $price,
            ":date" => $date,
            ":km" => $km,
            ":truck" => $truck
        ]);
        if ($switch) {
            $queryPrepared = $pdo->prepare("UPDATE TRUCKSTATUS SET STATUS = :status, updateDate = CURRENT_TIMESTAMP() WHERE truck = :truck");
            $queryPrepared->execute([
                ":truck" => $truck,
                ":status" => 10
            ]);
        }
        header("Location: ../truckMaintenance.php");
    }
} else {
    echo "ko";
}