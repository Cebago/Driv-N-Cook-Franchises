<?php
require "../conf.inc.php";
require "../functions.php";

$day = array(
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi",
    "Dimanche",

);
for ($i = 0; $i < count($day); $i++) {
    if (isset($_POST["check" . $day[$i]]) && isset($_POST[$day[$i]]) ) {
        $thisday = $_POST[$day[$i]];
        if (preg_match("#(((?:2[0-3]|[01][0-9]):[0-5][0-9] - (?:2[0-3]|[01][0-9]):[0-5][0-9]) \/ ((?:2[0-3]|[01][0-9]):[0-5][0-9] - (?:2[0-3]|[01][0-9]):[0-5][0-9]) \/ ((?:2[0-3]|[01][0-9]):[0-5][0-9] - (?:2[0-3]|[01][0-9]):[0-5][0-9]))|(((?:2[0-3]|[01][0-9]):[0-5][0-9] - (?:2[0-3]|[01][0-9]):[0-5][0-9]) \/ ((?:2[0-3]|[01][0-9]):[0-5][0-9] - (?:2[0-3]|[01][0-9]):[0-5][0-9]))|(((?:2[0-3]|[01][0-9]):[0-5][0-9] - (?:2[0-3]|[01][0-9]):[0-5][0-9]))#", $thisday)) {
            echo "<br>" . $thisday . "<br>";
        }
    }
}
