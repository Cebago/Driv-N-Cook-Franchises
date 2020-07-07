<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (!isActivated() || !isFranchisee()) {
    header("Location: login.php");
}
include "header.php";
    include "navbar.php";


    ?>
    <div class="toast" id="toastOK" data-delay="2000" style="position: absolute; top: 0; right: 0;">
        <div class="toast-header">
            <strong class="mr-auto"><i class="fa fa-street-view"></i> Enregistrement de vos coordonées faites avec succès!</strong>
        </div>
    </div>

    <div class="toast" id="toastKO" style="position: absolute; top: 0; right: 0;">
        <div class="toast-header">
            <strong class="mr-auto"><i class="fa fa-exclamation-circle"></i>Enregistrement de vos coordonées faites avec succès!</strong>
        </div>
    </div>

    <main role="main">
        <section class="jumbotron text-center">
            <div class="container">
                <h1>Accueil franchisés</h1>
                <p id="holidayButton">
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
    <script src="scripts/locate.js"></script>
    <script>
        window.onload = function() {
            displayOrders();
            isOnHolidays(<?php echo getMyTruck($_SESSION["email"]); ?>);
        }
        setInterval(displayOrders, 15000)
    </script>

    <?php include "footer.php";
    $idTruck = getMyTruck($_SESSION["email"]);
    if(isOpen($idTruck)){
        echo '<script type="text/javascript">',
        'getLocation('.$idTruck.');',
        '</script>'
        ;
    }

