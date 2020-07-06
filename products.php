<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isConnected() && isActivated() && isFranchisee()) {
    include "header.php";
    include "navbar.php";
    ?>

<body>
<div class="col-md-11 mx-auto mt-5">
    <button class="btn btn-primary" data-toggle="modal" data-target="#productModal">Ajouter un produit</button>
</div>
<div class="col-md-11 mx-auto mt-5">
    <?php
    if (isset($_GET["error"])) {
    ?>
    <div class="alert alert-danger">
        Des erreurs ont été détectées, le produit n'a donc pas été créé.
    </div>
    <?php
    }
    ?>
    <div class="card">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Nom</th>
                    <th class="text-center">Prix</th>
                    <th class="text-center">Catégorie</th>
                    <th class="text-center">Ingrédients</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $pdo = connectDB();
            $queryPrepared = $pdo->prepare("SELECT idProduct, productName, productPrice, category FROM PRODUCTS");
            $queryPrepared->execute();
            $products = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

            $queryPrepared = $pdo->prepare("SELECT idCategory, categoryName FROM PRODUCTCATEGORY");
            $queryPrepared->execute();
            $categories = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

            foreach ($products as $product) { ?>
                <tr>
                    <th class="text-center"><?php echo $product["idProduct"] ?></th>
                    <td class="text-center"><?php echo $product["productName"] ?></td>
                    <td class="text-center"><?php echo number_format($product["productPrice"],2) . " €" ?></td>
                    <td class="text-center"><?php
                        if (!empty($product["category"])) {
                            $pdo = connectDB();
                            $queryPrepared = $pdo->prepare("SELECT categoryName FROM PRODUCTCATEGORY WHERE idCategory = :category");
                            $queryPrepared->execute([":category" => $product["category"]]);
                        } else {
                            echo "Aucune catégorie saise";
                        }
                        ?></td>
                    <td>
                        <ul>
                            <?php
                        $ingredients  = allIngredientsFromProduct($product["idProduct"]);
                        foreach ($ingredients as $ingredient) {
                            echo "<li>" . $ingredient["ingredientName"] . "</li>";
                        }
                        ?>
                        </ul>
                    </td>
                    <td class="text-center">
                        <?php
                        $queryPrepared = $pdo->prepare("SELECT status FROM PRODUCTSTATUS WHERE product = :product");
                        $queryPrepared->execute([":product" => $product["idProduct"]]);
                        $status = $queryPrepared->fetch(PDO::FETCH_ASSOC);
                        if (!empty($status))
                            $status = $status["status"];
                        if ($status == 19 || empty($status)) {
                        ?>
                        <a href="./functions/deleteProduct.php?id=<?php echo $product["idProduct"] ?>&status=20" class="btn btn-warning" >
                            Rendre ce produit indisponible
                        </a>
                        <?php
                        } else { ?>
                            <a href="./functions/deleteProduct.php?id=<?php echo $product["idProduct"] ?>&status=19" class="btn btn-success" >
                                Rendre ce produit disponible
                            </a>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="advantageModalLabel" aria-hidden="true">
    <form method="POST" action="./functions/addProduct.php">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="advantageModalLabel">Ajouter un produit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="productName">Nom</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Nom du produit" name="productName" aria-label="productName" aria-describedby="productName" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="productPrice">Prix</span>
                        </div>
                        <input type="number" min="0" step="any" class="form-control" placeholder="Prix" name="productPrice" aria-label="productPrice" aria-describedby="productPrice" required>
                    </div>
                    <?php
                    $pdo = connectDB();
                    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientCategory, idIngredient 
                                                FROM INGREDIENTS, STORE, WAREHOUSES, TRUCKWAREHOUSE, TRUCK 
                                                WHERE idIngredient = STORE.ingredient 
                                                  AND STORE.warehouse = idWarehouse 
                                                  AND STORE.available = TRUE 
                                                  AND TRUCKWAREHOUSE.warehouse = idWarehouse 
                                                  AND TRUCKWAREHOUSE.truck = idTruck 
                                                  AND truck = :truck
                                                  AND warehouseType = 'Camion';");
                    $truck = getMyTruck($_SESSION["email"]);
                    $queryPrepared->execute([
                        ":truck" => $truck
                    ]);
                    $ingredients = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($ingredients as $ingredient) {
                    ?>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" value="<?php echo $ingredient["idIngredient"] ?>" name="ingredients[]" id="<?php echo $ingredient["ingredientName"] ?>">
                        <label class="custom-control-label" for="<?php echo $ingredient["ingredientName"] ?>"><?php echo $ingredient["ingredientName"] ?></label>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="productCategory">Options</label>
                        </div>
                        <select class="custom-select" id="productCategory" name="productCategory">
                            <option selected value="">Choisir...</option>
                            <?php
                            foreach ($categories as $category) {
                                echo "<option value='" . $category["idCategory"] . "'>" . $category["categoryName"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </form>
</div>

    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}
?>