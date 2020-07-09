<?php
session_start();
require "../conf.inc.php";
require "../functions.php";

var_dump($_FILES);

$title = $_POST["idMenu"];
$target_dir = "../../img/menuImg/";
$uploadOk = 1;
$listOfErrors = [];
$target_file = $target_dir . basename($_FILES["menuImage"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["menuImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $listOfErrors[] = "Ce n'est pas une image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_dir . $title . $imageFileType)) {
    $listOfErrors[] = "Votre fichier existe déjà";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["menuImage"]["size"] > 5000000) {
    $listOfErrors[] = "Votre fichier est trop gros";
    $uploadOk = 0;
}
// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    $listOfErrors[] = "Seuls les extensions .gif, .png, . jpg et .jpeg sont acceptés";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    $listOfErrors[] = "Desolé votre fichier n'est pas envoyé";
    $_SESSION["errors"];
} else {
    if (move_uploaded_file($_FILES["menuImage"]["tmp_name"], $target_dir . $title . "." . $imageFileType)) {
        $img = $target_dir . $title . "." . $imageFileType;
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("UPDATE MENUS SET menuImage = :image WHERE idMenu = :id");
        $queryPrepared->execute([
            ":image" => $img,
            ":id" => $title
        ]);
    } else {
        $listOfErrors[] = "Une erreur a été rencontrée lors du téléchargement";
    }
    $_SESSION["errors"];
}
header("Location: ../menus.php");