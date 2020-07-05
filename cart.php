<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

if (isConnected() && isActivated() && isFranchisee()) {

    include 'header.php';
    include 'navbar.php';
    $idCart = lastCart($_SESSION["email"]);

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory, quantity, idIngredient
                                            FROM INGREDIENTS, CARTINGREDIENT, CART, USER 
                                            WHERE CARTINGREDIENT.ingredient = idIngredient 
                                              AND CARTINGREDIENT.cart = idCart 
                                              AND CART.user = idUser 
                                              AND emailAddress = :user
                                              AND idCart = :id");
    $queryPrepared->execute([
        ":user" => $_SESSION["email"],
        ":id" => $idCart
    ]);
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Panier</h1>
            <?php
            $pdo = connectDB();
            $queryPrepared = $pdo->prepare("SELECT firstname, lastname FROM USER WHERE emailAddress = :email");
            $queryPrepared->execute([
                ":email" => $_SESSION["email"]
            ]);
            $user = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
            $firstname = $user[0]["firstname"];
            $lastname = $user[0]["lastname"];
            echo '<p class="lead">Ceci est votre panier ' . $firstname . '&nbsp;' . $lastname . '</p>';
            ?>
        </div>
    </div>
    <div class="album py-5">
        <div class="row col-md-12 mx-auto">
            <?php
            if (!empty($result)) {
                ?>
                <div class="card mx-auto">
                <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom de l'ingrédient</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Prix unitaire</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Ajouter/Supprimer</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($result as $value) {
                    $queryPrepared = $pdo->prepare("SELECT price FROM INGREDIENTS, STORE WHERE ingredient = idIngredient AND idIngredient = :ingredient");
                    $ingredient = $value["idIngredient"];
                    $queryPrepared->execute([
                        ":ingredient" => $ingredient
                    ]);
                    $price = $queryPrepared->fetch(PDO::FETCH_ASSOC);
                    $price = $price["price"];
                    $finalPrice = $price * $value["quantity"];
                    ?>
                    <tr>
                        <td><?php echo $value["ingredientCategory"] ?></td>
                        <td><?php echo $value["ingredientName"]; ?></td>
                        <td id="<?php echo $value['idIngredient']; ?>"><?php echo $value["quantity"] ?></td>
                        <td id="<?php echo 'priceUnitary' . $value['idIngredient']; ?>"><?php echo number_format($price, 2) . "€" ?></td>
                        <td id="<?php echo 'priceId' . $value['idIngredient']; ?>"
                            class="final"><?php echo number_format($finalPrice, 2) . "€" ?></td>
                        <td class="text-center">
                            <button type="button"
                                    onclick="deleteQuantity(<?php echo $idCart . ", " . $value["idIngredient"]; ?>, this)"
                                    class="btn btn-sm btn-danger ml-2"><i class="fas fa-minus"></i></button>
                            <button type="button"
                                    onclick="addQuantity(<?php echo $idCart . ", " . $value["idIngredient"]; ?>)"
                                    class="btn btn-sm btn-success ml-2"><i class="fas fa-plus"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="1">
                            <strong>TOTAL :</strong>
                        </td>
                        <td colspan="3"></td>
                        <td colspan="1" id="total"></td>
                        <td colspan="1"></td>
                    </tr>
                    </tbody>
                    </table>
                    </div>

                    <?php
                }
            } else {
                echo "<div class='col-md-6 mx-auto'><p>Vous n'avez rien dans votre panier</p></div>";
            }
            ?>
        </div>
        <?php
        if (!empty($result)) {
            ?>
            <div class="col-md-1 mx-auto mt-5">
                <a href="payment.php" class="btn btn-dark"><i class="fas fa-credit-card"></i>&nbsp;Payer le panier</a>
            </div>
            <?php
        }
        ?>
    </div>
    <script>
        function deleteQuantity(cart, ingredient, thisParameter) {
            let input = document.getElementById(ingredient);
            let inputPrice = document.getElementById('priceId' + ingredient);
            let inputPriceUnitary = document.getElementById('priceUnitary' + ingredient);
            if (Number(input.innerText) >= 1) {
                input.innerText = String(Number(input.innerText) - 1);
                inputPrice.innerText = (parseFloat(inputPrice.innerText) - parseFloat(inputPriceUnitary.innerText)).toFixed(2) + '€';
                thisParameter.removeAttribute("disabled");
                let cartQty = document.getElementById("cartQty");
                cartQty.innerHTML = "<i class='fas fa-shopping-cart'></i>&nbsp;" + Number(Number(cartQty.innerText) - 1);
                const request = new XMLHttpRequest();
                request.onreadystatechange = function () {
                    if (request.readyState === 4) {
                        if (request.status === 200) {
                            if (request.responseText !== "") {
                                alert(request.responseText);
                            }
                        }
                    }
                };
                request.open('GET', 'functions/deleteIngredient.php?cart=' + cart + '&ingredient=' + ingredient);
                request.send();
            } else {
                thisParameter.setAttribute("disabled", "true");
            }
            displayFinal();
        }

        function addQuantity(cart, ingredient) {
            let input = document.getElementById(ingredient);
            let inputPrice = document.getElementById('priceId' + ingredient);
            let inputPriceUnitary = document.getElementById('priceUnitary' + ingredient);
            input.innerText = Number(input.innerText) + 1;
            let cartQty = document.getElementById("cartQty");
            cartQty.innerHTML = "<i class='fas fa-shopping-cart'></i>&nbsp;" + Number(Number(cartQty.innerText) + 1);
            inputPrice.innerText = (parseFloat(inputPrice.innerText) + parseFloat(inputPriceUnitary.innerText)).toFixed(2) + '€';
            const request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 4) {
                    if (request.status === 200) {
                        if (request.responseText !== "") {
                            alert(request.responseText);
                        }
                    }
                }
            };
            request.open('GET', 'functions/addIngredient.php?cart=' + cart + '&ingredient=' + ingredient);
            request.send();
            displayFinal();
        }

        function displayFinal() {
            let td = document.getElementsByClassName("final");
            let finalPrice = 0;
            for (let i = 0; i < td.length; i++) {
                let price = td[i].innerText.split('€');
                finalPrice = Number(finalPrice + Number(price[0]));
            }
            const final = document.getElementById("total");
            final.innerText = finalPrice.toFixed(2) + "€"
        }

        window.onload = function () {
            displayFinal();
        }
    </script>
    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}
?>