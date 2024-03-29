<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

if (isConnected() && isActivated() && isFranchisee()) {
    if (isset($_GET["warehouse"])) {
        include 'header.php';
        include 'navbar.php';
        $pdo = connectDB();

        $queryPrepared = $pdo->prepare("SELECT warehouseName FROM WAREHOUSES WHERE idWarehouse = :warehouse");
        $queryPrepared->execute([
            ":warehouse" => $_GET["warehouse"]
        ]);
        $truck = $queryPrepared->fetch(PDO::FETCH_ASSOC);

        $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory, idIngredient, price FROM INGREDIENTS, STORE, WAREHOUSES WHERE idIngredient = ingredient AND warehouse = idWarehouse AND warehouse = :warehouse");
        $queryPrepared->execute([
            ":warehouse" => $_GET["warehouse"]
        ]);
        $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <script type="text/javascript">
            function addQuantity(name) {
                let input = document.getElementsByName(name);
                input[0].value = parseInt(input[0].value, 10) + 1;

            }

            function deleteQuantity(name) {
                let input = document.getElementsByName(name);
                if (parseInt(input[0].value, 10) > 0) {
                    input[0].value = parseInt(input[0].value, 10) - 1;
                }
            }
        </script>
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Entrepôt</h1>
                <p class="lead">Achat entrepôt</p>
                <?php
                echo "<p class='lead'>" . $truck["warehouseName"] . "</p>"
                ?>
            </div>
        </div>
        <form method="POST" action="addQuantity.php">
            <div class="album py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <?php
                        foreach ($result as $value) {
                            ?>
                            <div class="col-md-4">
                                <div class="card mb-4 shadow-sm">
                                        <title><?php echo $value["ingredientName"]; ?></title>

                                        <img src="<?php echo $value["ingredientImage"]; ?>"  fill="#eceeef"
                                              dy=".3em" height="225"></img>
                                    <div class="card-body">
                                        <p class="card-text"><?php echo $value["ingredientName"] . " - " . number_format($value["price"], 2) . "€"; ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-sm btn-danger ml-1"
                                                    onclick="deleteQuantity(<?php echo $value["idIngredient"]; ?>)">
                                                <i class="fas fa-minus"></i></button>
                                            <input class="border ml-1 p-2 w-25 form-control"
                                                   name="<?php echo $value["idIngredient"]; ?>" value="0" readonly>
                                            <button type="button"
                                                    onclick="addQuantity(<?php echo $value["idIngredient"]; ?>)"
                                                    class="btn btn-sm btn-success ml-1"><i class="fas fa-plus"></i>
                                            </button>

                                            <small class="text-muted"
                                                   type="ml-5"><?php echo $value["ingredientCategory"]; ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="fixed-bottom text-right">
                <button type="submit" class="btn btn-lg btn-secondary mr-5 mb-5"><i class="fas fa-cart-plus"></i>&nbsp;Ajouter
                </button>
            </div>
        </form>
        <?php
        include "footer.php";
    } else {
        header("Location: orderWarehouse.php?warehouse=1");
    }
} else {
    header("Location: login.php");
}
?>