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
    <button class="btn btn-primary" data-toggle="modal" data-target="#categoryModal">Ajouter une catégorie</button>
</div>
<div class="col-md-11 mx-auto mt-5 card">
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Nom de la catégorie</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT idCategory, categoryName FROM PRODUCTCATEGORY");
        $queryPrepared->execute();
        $categories = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as $category) { ?>
            <tr>
                <th><?php echo $category["idCategory"] ?></th>
                <td><?php echo $category["categoryName"] ?></td>
                <td><a href="./functions/deleteCategory.php?id=<?php echo $category["idCategory"] ?>"
                       class="btn btn-warning">Supprimer cette catégorie</a></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel"
     aria-hidden="true">
    <form method="POST" action="./functions/addCategory.php">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Ajouter une catégorie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="categoryName">Nom</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Nom de la catégorie" name="categoryName"
                               aria-label="categoryName" aria-describedby="categoryName" required>
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