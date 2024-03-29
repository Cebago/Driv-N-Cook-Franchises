<?php
require "../conf.inc.php";
require "../functions.php";
session_start();

$day = array(
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi",
    "Dimanche",
);

$newDay = [];
$error = false;
$match = array(
    0 => "((?:2[0-3]|[01][0-9]):[0-5][0-9] - (?:2[0-3]|[01][0-9]):[0-5][0-9])"
);
$listOfErrors = [];
for ($j = 1; $j < 10; $j++) {
    $match = array_merge($match, [$j => $match[$j - 1] . " \/ " . $match[0]]);
}

for ($i = 0; $i < count($day); $i++) {
    if (isset($_POST["check" . $day[$i]]) && isset($_POST[$day[$i]])) {
        $thisday = $_POST[$day[$i]];
        $number = mb_substr_count($thisday, "/");
        if (!preg_match("#^" . $match[$number] . "$#", $thisday)) {
            $error = true;
            $listOfErrors[] = "Merci de saisir le bon format horaire pour " . $day[$i];
        } else {
            $thisday = preg_replace("# - #", " / ", $thisday);
            $newDay = array_merge($newDay, [$day[$i] => preg_split("# / #", $thisday)]);
            for ($pos = 0; $pos < count($newDay[$day[$i]]) - 1; $pos++) {
                if ($newDay[$day[$i]][$pos] > $newDay[$day[$i]][$pos + 1]) {
                    $error = true;
                    $listOfErrors[] = "Il n'est pas possible que vous ouvriez une deuxième fois avant d'avoir fermé.";
                }
            }
        }
    }
}
if ($error) {
    unset($_POST);
    $_SESSION["errors"] = $listOfErrors;
    header("Location: ../truckInfo.php");
} else {
    $pdo = connectDB();
    $truck = getMyTruck($_SESSION["email"]);
    $queryPrepared = $pdo->prepare("DELETE FROM OPENDAYS WHERE truck=:truck");
    $queryPrepared->execute([
        ":truck" => $truck
    ]);
    //$user = $_SESSION["user"];
    $queryPrepared = $pdo->prepare("INSERT INTO OPENDAYS (openDay, startHour, endHour, truck) VALUES (:day, :start, :end, :truck)");
    for ($i = 0; $i < count($day); $i++) {
        if (isset($newDay[$day[$i]])) {
            for ($pos = 0; $pos < count($newDay[$day[$i]]); $pos += 2) {
                if (count($newDay[$day[$i]]) > 1) {
                    $queryPrepared->execute([
                        ":day" => $day[$i],
                        ":start" => $newDay[$day[$i]][$pos],
                        ":end" => $newDay[$day[$i]][$pos + 1],
                        ":truck" => $truck
                    ]);
                }
            }
        }
    }
    header("Location: ../truckInfo.php");
}
