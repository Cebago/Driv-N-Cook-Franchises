<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isConnected() && isActivated() && (isAdmin() || isFranchisee())) {
    include "header.php";
    include "navbar.php";

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idCart, idOrder, date_format(orderDate, '%d/%m/%Y') as orderDate, date_format(orderDate, '%H:%i') as orderHour, orderPrice, truckName FROM CART, ORDERS, USER, TRUCK WHERE idCart = cart AND CART.user = idUser AND emailAddress = :email AND orderType = 'Commande Franchisé' AND TRUCK.user = idUser");
    $queryPrepared->execute([
        ":email" => $_SESSION["email"]
    ]);
    $orders = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <div class="col-md-11 mx-auto mt-3 mb-3">
        <div class="accordion" id="accordionExample">
            <?php
            foreach ($orders as $order) {
            ?>
            <div class="card">
                <div class="card-header" id="<?php echo 'heading' . $order['idOrder'] ?>">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="<?php echo '#collapse' . $order['idOrder'] ?>" aria-expanded="true" aria-controls="<?php echo 'collapse' . $order['idOrder'] ?>">
                            <?php
                            echo "Commande du " . $order["orderDate"] . " à " . $order["orderHour"];
                            ?>
                        </button>
                    </h2>
                </div>

                <div id="<?php echo 'collapse' . $order['idOrder'] ?>" class="collapse" aria-labelledby="<?php echo 'heading' . $order['idOrder'] ?>" data-parent="#accordionExample">
                    <div class="card-body">
                        <p>
                            <strong>Total:</strong>
                            <?php
                            echo number_format($order["orderPrice"], 2) . "€";
                            ?>
                        </p>
                        <p>
                            <strong>Camion:</strong>
                            <?php
                            echo $order["truckName"];
                            ?>
                        </p>
                        <ul>
                            <?php
                            $ingredients = getIngredients($order['idCart']);
                            foreach ($ingredients as $ingredient) {
                                echo "<li>" . $ingredient['ingredientName'] . "&nbsp;x" . $ingredient["quantity"] . "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php
    include "footer.php";
} else {
    header("Location; login.php");
}
