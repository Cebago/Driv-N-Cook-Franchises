<?php
session_start();
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

if (isConnected() && isActivated() && (isAdmin() || isFranchisee())) {
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory, quantity, idIngredient FROM INGREDIENTS, CARTINGREDIENT, CART, USER WHERE CARTINGREDIENT.ingredient = idIngredient AND CARTINGREDIENT.cart = idCart AND CART.user = idUser AND  user = 1");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <script type="text/javascript">

        function addQuantity(count) {
            let input = document.getElementsByName("quantity");
            input[count - 1].value = parseInt(input[count - 1].value, 10) + 1;

        }

        function deleteQuantity(count) {
            let input = document.getElementsByName("quantity");
            if (parseInt(input[count - 1].value, 10) > 0) {
                input[count - 1].value = parseInt(input[count - 1].value, 10) - 1;
            }

        }

    </script>

    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
                <?php
                $count = 0;
                foreach ($result as $value) {
                    $count++;
                    ?>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                                 xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice"
                                 focusable="false" role="img" aria-label="Placeholder: Thumbnail">
                                <title><?php echo $value["ingredientName"] ?></title>
                                <rect width="100%" height="100%" fill="#55595c"/>
                                <text x="50%" y="50%" fill="#eceeef"
                                      dy=".3em"><?php echo $value["ingredientImage"]; ?></text>
                            </svg>
                            <div class="card-body">
                                <p class="card-text"><?php echo $value["ingredientName"]; ?></p>
                                <div class="d-flex justify-content-between align-items-center">

                                    <button type="button" class="btn btn-sm btn-danger ml-1"
                                            onclick="deleteQuantity(<?php echo $count . "," . $value["idIngredient"]; ?>)">
                                        <i class="fas fa-minus"></i></button>
                                    <input class="border ml-1 p-2 w-25" name="quantity"
                                           value="<?php echo $value["quantity"]; ?>" readonly>
                                    <button type="button"
                                            onclick="addQuantity(<?php echo $count . "," . $value["idIngredient"]; ?>)"
                                            class="btn btn-sm btn-success ml-1"><i class="fas fa-plus"></i></button>

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


    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}
?>