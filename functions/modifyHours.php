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

$error = false;
$match = array(
    0 => "((?:2[0-3]|[01][0-9]):[0-5][0-9] - (?:2[0-3]|[01][0-9]):[0-5][0-9])"
);
$listOfErrors = "";
for ($j = 1; $j < 10; $j++) {
    $match = array_merge($match, [$j => $match[$j-1] . " \/ " . $match[0]]);
}

for ($i = 0; $i < count($day); $i++) {
    if (isset($_POST["check" . $day[$i]]) && isset($_POST[$day[$i]]) ) {
        $thisday = $_POST[$day[$i]];
        $number = mb_substr_count($thisday, "/");
        echo "<br>" . mb_substr_count($thisday, "/") . "</br>";
        echo $thisday;
        echo "<br>" . $match[$number] . "</br>";
        if (preg_match("#^" . $match[$number] . "$#", $thisday)) {
            echo $thisday;
        } else {
            $error = true;
            $listOfErrors = "Merci de saisir le bon format horaire pour " . $day[$i];
        }
    }
}
if ($error) {
    echo "Erreurs !!!";
} else {
    echo "DATABASE";
}
