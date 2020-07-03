<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

if (count($_POST) == 8 && isset($_POST["truckName"], $_POST["beginDate"], $_POST["endDate"], $_POST["startHour"],
        $_POST["endHour"], $_POST["address"], $_POST["city"], $_POST["zip"]
    )) {
    $name = $_POST["truckName"];
    $beginDate = $_POST["beginDate"];
    $endDate = $_POST["endDate"];
    $startHour = $_POST["startHour"];
    $endHour = $_POST["endHour"];
    $address = $_POST["address"];
    $city = htmlspecialchars(trim($_POST["city"]));
    $zip = $_POST["zip"];

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
    var_dump($time);
    if ( $time[0] < 0 || $time[0] > 23 || )

    var_dump($listOfErrors);

} else {
    die("Ne pas modifier le fomulaire");
}