<?php
session_start();
require "conf.inc.php";
require "functions.php";
if (isActivated() && isFranchisee()) {
    include "header.php";
    include "navbar.php"; ?>


    <main role="main">
        <section class="jumbotron text-center">
            <div class="container">
                <h1>Accueil franchisés</h1>
                <p id="holidayButton">
                    <button class="btn btn-primary my-2">Fermer le camion pour cause de congés</button>
                </p>
            </div>
        </section>

        <div class="album py-5">
            <div class="container">
                <div class="row" id="ordersList"></div>
            </div>
        </div>
    </main>
    <script src="scripts/order.js"></script>
    <script>
        window.onload = function() {
            displayOrders();
            isOnHolidays();
        }
        setInterval(displayOrders, 15000)
    </script>
    <?php include "footer.php";
} else {
    header("Location: login.php");
}
?>