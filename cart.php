<?php
session_start();
require 'conf.inc.php';
require 'functions.php';
include 'header.php';


$pdo = connectDB();
$queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory, quantity, idIngredient
FROM INGREDIENTS, CARTINGREDIENT, CART, USER 
WHERE CARTINGREDIENT.ingredient = idIngredient AND CARTINGREDIENT.cart = idCart AND CART.user = idUser AND  user = 1");
$queryPrepared->execute();
$result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

$queryPrepared = $pdo->prepare("SELECT idCart FROM CART WHERE user = 1 ORDER BY idCart DESC;");
$queryPrepared->execute();
$idCart = $queryPrepared->fetch(PDO::FETCH_ASSOC);
$idCart = $idCart["idCart"];


/*$queryPrepared2 = $pdo->prepare("SELECT ingredient, idIngredient, quantity FROM INGREDIENTS, CARTINGREDIENT  WHERE ingredient = idIngredient ");
$queryPrepared2->execute();
$result2 = $queryPrepared2->fetchAll(PDO::FETCH_ASSOC);*/


?>
    <div class="album py-5 bg-light">
        <div class="container" id="cart">
            <div class="row">
                <div class="card mx-auto">
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="Nom de la catégorie">#</th>
                            <th scope="col">Nom de l'ingrédient</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Prix unitaire</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Ajouter/Supprimer</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        foreach ($result
                        as $value) {
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
                                <td name="quantityId"><?php echo $value["quantity"] ?></td>
                                <td name="priceUnitary"><?php echo number_format($price, 2) . "€" ?></td>
                                <td name="priceId"><?php echo number_format($finalPrice, 2) . "€" ?></td>
                                <td>
                                    <button type="button"
                                            onclick="addQuantity(<?php echo $idCart.", ".$value["idIngredient"];?>)"
                                            class="btn btn-sm btn-success ml-1"><i class="fas fa-plus"></i></button>
                                    <button type="button"
                                            onclick="deleteQuantity(<?php echo $idCart.", ".$value["idIngredient"].", ".$value["quantity"];?>)"
                                            class="btn btn-sm btn-danger ml-1" id="deleteButton" ><i class="fas fa-minus"></i></button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script>

        function deleteQuantity(cart,ingredient,price) {
            let input = document.getElementsByName('quantityId');
            let inputPrice = document.getElementsByName('priceId');
            let inputPriceUnitary = document.getElementsByName('priceUnitary');

            if (input >= 0){
                input[0].innerText = parseInt(input[0].innerText, 10) - 1;

                inputPrice[0].innerText = (parseFloat(inputPrice[0].innerText, 10) - parseFloat(inputPriceUnitary[0].innerText, 10)).toFixed(2)+'€';

                const request = new XMLHttpRequest();
                request.onreadystatechange = function() {
                    if(request.readyState === 4) {
                        if(request.status === 200) {
                            if (request.responseText !== "") {
                                alert(request.responseText);
                            }
                        }
                    }
                };
                request.open('GET', 'functions/deleteIngredient.php?cart='+cart+'&ingredient='+ingredient);
                request.send();
            }else{
                document.getElementById('deleteButton').setAttribute("disabled","true");

            }

        }

        function addQuantity(cart,ingredient) {

            let input = document.getElementById('quantityId');
            let inputPrice = document.getElementById('priceId');
            let inputPriceUnitary = document.getElementById('priceUnitary');

            input.innerText = parseInt(input.innerText, 10) + 1;

            inputPrice.innerText = (parseFloat(inputPrice.innerText, 10) + parseFloat(inputPriceUnitary.innerText, 10)).toFixed(2)+'€';

            document.getElementById('deleteButton').setAttribute("disabled", "false");

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


        }

    </script>


<?php

/* foreach ($result as $value) {
     echo "<pre>";
  print_r($value);
  echo "</pre>";
  }
*/


include "footer.php";
?>