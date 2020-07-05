<?php
session_start();
require "../conf.inc.php";
require "../functions.php";


if (isset($_SESSION["email"])) {
    logout($_SESSION["email"]);
}

session_destroy();
header("Location: ../login.php");
