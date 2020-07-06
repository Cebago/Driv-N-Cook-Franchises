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
<div class="col-md-11 mx-auto mt-5 card">
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prix</th>
            <th>Catégorie</th>
            <th>Action</th>
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
                <th><?php echo $product["idProduct"] ?></th>
                <td><?php echo $product["productName"] ?></td>
                <td><?php echo number_format($product["productPrice"],2) . " €" ?></td>
                <td><?php
                    if (!empty($product["category"])) {
                        $pdo = connectDB();
                        $queryPrepared = $pdo->prepare("SELECT categoryName FROM PRODUCTCATEGORY WHERE idCategory = :category");
                        $queryPrepared->execute([":category" => $product["category"]]);
                    } else {
                        echo "Aucune catégorie saise";
                    }
                    ?></td>
                <td><a href="./functions/deleteProduct.php?id=<?php echo $product["idProduct"] ?>" class="btn btn-warning" >Supprimer ce produit</a></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
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
                        <input type="number" min="0" class="form-control" placeholder="Prix" name="productPrice" aria-label="productPrice" aria-describedby="productPrice" required>
                    </div>
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