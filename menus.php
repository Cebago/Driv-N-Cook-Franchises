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
    <button class="btn btn-primary" data-toggle="modal" data-target="#menuModal">Ajouter un menu</button>
</div>
<div class="col-md-11 mx-auto mt-5">
    <?php
    if (isset($_GET["error"])) {
        ?>
        <div class="alert alert-danger">
            Des erreurs ont été détectées, le menu n'a donc pas été créé.
        </div>
        <?php
    }
    if (isset($_SESSION["errors"])) {
        ?>
        <div class="alert alert-danger">
            <?php
            foreach ($_SESSION["errors"] as $error) {
                echo "<li>" . $error . "</li>";
            }
            unset($_SESSION["errors"]);
            ?>
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
                <th class="text-center">Composition</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $pdo = connectDB();
            $menus = getMyMenus(getMyTruck($_SESSION["email"]));

            $queryPrepared = $pdo->prepare("SELECT idCategory, categoryName FROM PRODUCTCATEGORY");
            $queryPrepared->execute();
            $categories = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

            foreach ($menus as $menu) { ?>
                <tr>
                    <th class="text-center"><?php echo $menu["idMenu"] ?></th>
                    <td class="text-center"><?php echo $menu["menuName"] ?></td>
                    <td class="text-center"><?php echo number_format($menu["menuPrice"], 2) . " €" ?></td>
                    <td>
                        <ul>
                            <?php
                            $products = allProductFromMenu($menu["idMenu"]);
                            if (!empty($products)) {
                                foreach ($products as $product) {
                                    echo "<li>" . $product["productName"] . "<ul>";
                                    $ingredients = allIngredientsFromProduct($product["idProduct"]);
                                    foreach ($ingredients as $ingredient) {
                                        echo "<li>" . $ingredient["ingredientName"] . "</li>";
                                    }
                                    echo "</ul></li>";
                                }
                            }
                            ?>
                        </ul>
                    </td>
                    <td class="text-center">
                        <?php
                        $status = statusOfMenus($menu["idMenu"]);
                        if (!empty($status))
                            $status = $status["status"];
                        if ($status == 22 || empty($status)) {
                            ?>
                            <a href="./functions/deleteMenus.php?id=<?php echo $menu["idMenu"] ?>&status=23"
                               class="btn btn-warning">
                                Rendre ce menu indisponible
                            </a>
                            <?php
                        } else { ?>
                            <a href="./functions/deleteMenus.php?id=<?php echo $menu["idMenu"] ?>&status=22"
                               class="btn btn-success">
                                Rendre ce menu disponible
                            </a>
                            <?php
                        }
                        ?>
                        <button class="btn btn-info" data-toggle="modal" data-target="#imageUpload"
                                onclick="update(<?php echo $menu['idMenu'] ?>)">Uploader une image
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
    <form method="POST" action="./functions/addMenu.php">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuModalLabel">Ajouter un menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="menuName">Nom</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Nom du menu" name="menuName"
                               aria-label="menuName" aria-describedby="menuName" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="menuPrice">Prix</span>
                        </div>
                        <input type="number" min="0" step="any" class="form-control" placeholder="Prix" name="menuPrice"
                               aria-label="menuPrice" aria-describedby="menuPrice" required>
                    </div>
                    <?php
                    $pdo = connectDB();
                    $truck = getMyTruck($_SESSION["email"]);
                    $products = getMyProducts($truck);
                    foreach ($products as $product) {
                        ?>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input"
                                   value="<?php echo $product["idProduct"] ?>" name="products[]"
                                   id="<?php echo $product["productName"] ?>">
                            <label class="custom-control-label"
                                   for="<?php echo $product["productName"] ?>"><?php echo $product["productName"] ?></label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="imageUpload" tabindex="-1" role="dialog" aria-labelledby="uploadImageLabel"
     aria-hidden="true">
    <form method="POST" action="./functions/uploadImageMenu.php" enctype="multipart/form-data">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadImageLabel">Ajouter un menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="menuID">Numéro du menu</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Numéro du menu" name="idMenu" id="idMenu"
                               readonly required aria-label="Username" aria-describedby="menuID">
                    </div>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile01" name="menuImage"
                                   aria-describedby="inputGroupFileAddon01" required>
                            <label class="custom-file-label" for="inputGroupFile01">Choisir un fichier</label>
                        </div>
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

<script src="scripts/menus.js"></script>

    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}
?>