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
            $queryPrepared = $pdo->prepare("SELECT idMenu, menuName, menuPrice FROM MENUS");
            $queryPrepared->execute();
            $menus = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

            $queryPrepared = $pdo->prepare("SELECT idCategory, categoryName FROM PRODUCTCATEGORY");
            $queryPrepared->execute();
            $categories = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

            foreach ($menus as $menu) { ?>
                <tr>
                    <th class="text-center"><?php echo $menu["idMenu"] ?></th>
                    <td class="text-center"><?php echo $menu["menuName"] ?></td>
                    <td class="text-center"><?php echo number_format($menu["menuPrice"],2) . " €" ?></td>
                    <td>
                        <ul>
                            <?php
                            $products = allProductFromMenu($menu["idMenu"]);
                            if (!empty($products)) {
                                foreach ($products as $product) {
                                    echo "<li>" . $product["productName"] . "<ul>";
                                    $ingredients  = allIngredientsFromProduct($product["idProduct"]);
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
                        $queryPrepared = $pdo->prepare("SELECT status FROM MENUSSTATUS WHERE menus = :menu");
                        $queryPrepared->execute([":menu" => $menu["idMenu"]]);
                        $status = $queryPrepared->fetch(PDO::FETCH_ASSOC);
                        if (!empty($status))
                            $status = $status["status"];
                        if ($status == 22 || empty($status)) {
                            ?>
                            <a href="./functions/deleteMenus.php?id=<?php echo $menu["idMenu"] ?>&status=23" class="btn btn-warning" >
                                Rendre ce menu indisponible
                            </a>
                            <?php
                        } else { ?>
                            <a href="./functions/deleteMenus.php?id=<?php echo $menu["idMenu"] ?>&status=22" class="btn btn-success" >
                                Rendre ce menu disponible
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
                        <input type="text" class="form-control" placeholder="Nom du menu" name="menuName" aria-label="menuName" aria-describedby="menuName" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="menuPrice">Prix</span>
                        </div>
                        <input type="number" min="0" step="any" class="form-control" placeholder="Prix" name="menuPrice" aria-label="menuPrice" aria-describedby="menuPrice" required>
                    </div>
                    <?php
                    $pdo = connectDB();
                    $queryPrepared = $pdo->prepare("SELECT idProduct, productName FROM PRODUCTS");
                    $truck = getMyTruck($_SESSION["email"]);
                    $queryPrepared->execute([
                        ":truck" => $truck
                    ]);
                    $products = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($products as $product) {
                        ?>
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" value="<?php echo $product["idProduct"] ?>" name="products[]" id="<?php echo $product["productName"] ?>">
                            <label class="custom-control-label" for="<?php echo $product["productName"] ?>"><?php echo $product["productName"] ?></label>
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

    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}
?>