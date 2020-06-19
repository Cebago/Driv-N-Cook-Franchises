<?php
session_start();
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

if (isConnected() && isActivated() && (isAdmin() || isFranchisee())) {
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory, idIngredient FROM INGREDIENTS");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
?>

    <script type="text/javascript">
        function addQuantity(count, name) {
            let input = document.getElementsByName(name);
            let value = parseInt(input[0].value, 10) + 1;
            input[0].value = value;
        }
        function deleteQuantity(count, name) {
            let input = document.getElementsByName(name);
            if (parseInt(input[0].value, 10) > 0) {
                let value = parseInt(input[0].value, 10) - 1;
                input[0].value = value;
            }
        }
    </script>

    <form method="POST" action="test.php">
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
                                        <input class="border ml-1 p-2 w-25 form-control"
                                               name="<?php echo $value["idIngredient"]; ?>" value="0" readonly>
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

        <div class="fixed-bottom text-right">
            <button type="submit" class="btn btn-lg btn-secondary mr-5  mb-2"> Ajouter</button>
        </div>

    </form>


<?php
    include "footer.php";
} else {
    header("Location: login.php");
}
?>