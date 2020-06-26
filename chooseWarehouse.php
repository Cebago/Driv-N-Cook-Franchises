<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

if (isConnected() && isActivated() && (isAdmin() || isFranchise())) {

    include 'header.php';
    include 'navbar.php';
    ?>

    <form class="col-md-6 mx-auto mt-5 mb-5" action="orderWarehouse.php" method="GET">
        <label>Choisir un entrepôt dans lequel commander</label>
        <select class="custom-select" name="warehouse" onchange="deleteFirst(this)">
            <option selected>Choisir ... </option>
            <?php
                $pdo = connectDB();
                $queryPrepared = $pdo->prepare("SELECT idWarehouse, warehouseName FROM WAREHOUSES WHERE warehouseType = 'Entrepôt'");
                $queryPrepared->execute();
                $warehouses = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
                foreach ($warehouses as $warehouse) {
                    echo '<option value="' . $warehouse['idWarehouse'] .'">' . $warehouse['warehouseName'] . '</option>';
                }
            ?>
        </select>
        <button class="btn btn-primary mt-5 mb-5"><i class="fas fa-cart-arrow-down"></i>&nbsp;Commander</button>
    </form>


<script src="scripts/choose.js"></script>
    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}
