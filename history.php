<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isConnected() && isActivated() && (isAdmin() || isFranchisee())) {
    include "header.php";
    include "navbar.php";
?>










<?php
    include "footer.php";
} else {
    header("Location; login.php");
}
