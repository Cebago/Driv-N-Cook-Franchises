<?php
session_start();
require "conf.inc.php";
require "functions.php";
if (!isActivated() || !isFranchisee())
    header("Location: login.php");

include "header.php";
    include "navbar.php";


    ?>


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
    <script>
        window.onload = function() {
            displayOrders();
            isOnHolidays(<?php echo getMyTruck($_SESSION["email"]); ?>);
        }
        setInterval(displayOrders, 15000)
    </script>

    <?php include "footer.php";
    if(isOpen($idTruck)){
        $idTruck = getMyTruck($_SESSION["email"]);
        echo '<script type="text/javascript">',
        'saveLocation('.$idTruck.');',
        '</script>'
        ;
    }

