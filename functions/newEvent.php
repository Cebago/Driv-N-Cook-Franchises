<?php
session_start();
require "../conf.inc.php";
require "../functions.php";
echo '<pre>';
print_r($_POST);
echo '</pre>';


if (count($_POST) == 12 && isset($_POST["truckName"], $_POST["eventName"], $_POST["beginDate"], $_POST["endDate"], $_POST["startHour"],
        $_POST["endHour"], $_POST["address"], $_POST["city"], $_POST["zip"], $_POST["type"], $_POST["eventDesc"], $_POST["img"]
    )) {
    $name = $_POST["truckName"];
    $eventName = $_POST["eventName"];
    $beginDate = $_POST["beginDate"];
    $endDate = $_POST["endDate"];
    $startHour = $_POST["startHour"];
    $endHour = $_POST["endHour"];
    $address = $_POST["address"];
    $city = htmlspecialchars(trim($_POST["city"]));
    $zip = $_POST["zip"];
    $type = $_POST["type"];
    $desc = $_POST["eventDesc"];
    $img = $_POST["img"];
    $idTruck = getMyTruck($_SESSION["email"]);
    $eventType = [
        0 => "Réservation",
        1 => "Dégustation"
    ];

    $error = false;
    $listOfErrors = [];

    $dates = explode("-", $beginDate);
    if (!checkdate($dates[1], $dates[2], $dates[0])) {
        $error = true;
        $listOfErrors[] = "La date de début saisie n'est pas correcte";
    }

    $dates = explode("-", $endDate);
    if (!checkdate($dates[1], $dates[2], $dates[0])) {
        $error = true;
        $listOfErrors[] = "La date de fin saisie n'est pas correcte";
    }

    $time = explode(":", $startHour);
    if ( $time[0] < 0 || $time[0] > 23 || $time[1] < 0 || $time[1] > 59) {
        $error = true;
        $listOfErrors[] = "L'heure de début saisie n'est pas correcte";
    }

    $time = explode(":", $endHour);
    if ( $time[0] < 0 || $time[0] > 23 || $time[1] < 0 || $time[1] > 59) {
        $error = true;
        $listOfErrors[] = "L'heure de fin saisie n'est pas correcte";
    }

    if (strlen($city) < 3 && strlen($city) > 50 ) {
        $error = true;
        $listOfErrors[] = "La ville saisie n'est pas correcte";
    }

    if ($type != 0 && $type != 1) {
        $error = true;
        $listOfErrors[] = "Ne pas modifier la lise de choix";
    }

    if ($error) {
        $_SESSION["errors"] = $listOfErrors;
        $_SESSION["input"] = $_POST;
        header("Location: ../createEvents.php");
    } else {
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("INSERT INTO EVENTS (eventType, eventName, eventAddress, eventCity, eventPostalCode, eventBeginDate, eventEndDate, eventStartHour, eventEndHour, eventDesc, eventImg) 
                                                    VALUES (:type, :name, :address, :city, :zip, :beginDate, :endDate, :startHour, :endHour, :eventDesc, :eventImg)");
        $queryPrepared->execute([
            ":type" => $eventType[$type],
            ":name" => $eventName,
            ":address" => $address,
            ":city" => $city,
            ":zip" => $zip,
            ":beginDate" => $beginDate,
            ":endDate" => $endDate,
            ":startHour" => $startHour,
            ":endHour" => $endHour,
            ":eventDesc" => $desc,
            ":eventImg" => $img,
        ]);
        $id = $pdo->lastInsertId();
        $queryPrepared = $pdo->prepare("INSERT INTO EVENTSTATUS(event, status) VALUES (:id, 15)");
        $queryPrepared->execute([
            ":id" => $id
        ]);

        $queryPrepared = $pdo->prepare("INSERT INTO HOST(event, truck) VALUES (:idEvent, :idTruck)");
        $queryPrepared->execute([
            ":idEvent" => $id,
            ":idTruck" => $idTruck
        ]);

        header("Location: ../viewEvents.php");
    }

} else {
    die("Ne pas modifier le fomulaire");
}