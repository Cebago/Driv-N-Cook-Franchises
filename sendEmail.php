<?php
require 'conf.inc.php';
require 'functions.php';


if (count($_POST) == 3
    && isset($_POST['server'])
    && isset($_POST['user'])
    && isset($_POST['email']) ) {

    $server = htmlspecialchars(strtoupper(trim($_POST['server'])));
    $user = $_POST['user'];
    $email = strtolower(trim($_POST['email']));
    $error = false;














} else {
    header('Location: login.php');
}