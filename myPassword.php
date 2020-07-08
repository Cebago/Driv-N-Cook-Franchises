<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

if (isConnected() && isActivated() && isFranchisee()) {
    include 'header.php';
    ?>
    <body>
    <?php include "navbar.php"; ?>
    <div class="col-md-11 mx-auto mt-5">
        <div class="mt-5">
            <h5 class="mx-auto" id="changePassword">Modifier mon mot de passe</h5>
            <div>
                <?php
                if (isset($_SESSION["errors"])) {
                    echo "<div class='alert alert-danger'>";
                    foreach ($_SESSION["errors"] as $error) {
                        echo "<li>" . $error;
                    }
                    echo "</div>";
                }
                unset($_SESSION["errors"]);
                ?>
            </div>
            <form class="col-md-11 mx-auto mt-5" action="./functions/updatePassword.php" method="POST">
                <div class="input-group flex-nowrap mt-1 col-md-5 mx-auto">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Ancien Mot de passe</span>
                    </div>
                    <input class="form-control" type="password" name="oldPassword" placeholder="Ancien Mot de passe"
                           required>
                </div>
                <div class="input-group flex-nowrap mt-1 col-md-5 mx-auto">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Nouveau Mot de passe</span>
                    </div>
                    <input class="form-control" type="password" placeholder="Nouveau mot de passe" name="newPassword"
                           required>
                </div>
                <div class="input-group flex-nowrap mt-1 col-md-5 mx-auto">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Confirmation du Mot de passe</span>
                    </div>
                    <input class="form-control" type="password" placeholder="Confirmation du nouveau mot de passe"
                           name="confirm" required>
                </div>
                <div class="mt-5 col-md-2 mx-auto">
                    <button class="btn btn-warning" type="submit">Changer de mot de passe</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    include 'footer.php';
} else {
    header("Location: login.php");
}
?>