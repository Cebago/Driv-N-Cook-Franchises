<?php
session_start();
require 'conf.inc.php';
require 'functions.php';

if (isConnected() && isActivated() && isFranchisee()) {

    include 'header.php';
    include 'navbar.php';

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientCategory, ingredientName FROM INGREDIENTS GROUP BY ingredientCategory");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="jumbotron">
        <h1 class="display-4">Mes ingrédients</h1>
        <p class="lead">Ajoutez vos ingrédients extérieurs ou rendez les indisponibles.</p>
        <hr class="my-4">
        <p class="lead">
            <button type="button" class="btn btn-success btn-sm data" data-toggle="modal" data-target="#mymodal"
                    onclick="">Ajouter un ingrédient
            </button>
        </p>
    </div>
    <?php
    if (isset($_SESSION["errors"])) {
        echo "<div class='alert alert-danger'>";
        foreach ($_SESSION["errors"] as $error) {
            echo "<li>" . $error;
        }
        echo "</div>";
    }
    unset($_SESSION["errors"]);
    ?>

    <div class="card w-75 mx-auto col-md-8 mx-auto">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Ingrédient</th>
                <th scope="col">Famille</th>
                <th scope="col" class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody id="ingredients"></tbody>
        </table>
    </div>
    <div class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="mymodal" aria-hidden="true">
        <form method="POST" action="./functions/addInBdd.php" enctype="multipart/form-data">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ajouter un ingrédient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="selectCategory">Catégorie</label>
                                <select class="form-control selectCategory" onchange="showCategory()"
                                        id="selectCategory" name="category" required>
                                    <option selected value="">Choisir une catégorie..</option>
                                    <?php foreach ($result as $value) {
                                        echo "<option value='" . $value["ingredientCategory"] . "'>" . $value["ingredientCategory"] . "</option>";
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group" id="selectDiv">
                                <label for="selectIngredientName" class="selectName" id="selectName">Nom</label>
                                <select class="form-control selectIngredientName" id="selectIngredientName"
                                        name="ingredient" required>
                                </select>
                            </div>
                            <div id="existingIngredient"></div>
                            <div class="form-check">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkbox" name="checkbox">
                                    <label class="custom-control-label" for="checkbox" onclick="addIngredient()">Mon
                                        ingredient n'existe pas</label>
                                </div>
                            </div>
                            <div class="form-group" id="deleteMe"></div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="switch" name="lastOne">
                                <label class="custom-control-label" for="switch">Dernier ingrédient</label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success" id="submitButton">
                            Ajouter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="scripts/scripts.js"></script>
    <script type="text/javascript">
        window.onload = getIngredientTruck;
    </script>
    <?php include "footer.php";
} else {
    header("Location: login.php");
}
?>

