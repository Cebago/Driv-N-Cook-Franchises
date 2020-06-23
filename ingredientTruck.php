<?php
session_start();
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

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

    <div class="card w-75 mx-auto">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Ingrédient</th>
                <th scope="col">Famille</th>
                <th scope="col">Actions</th>
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
                                    <option selected>Choisir une catégorie..</option>
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

    <script type="text/javascript">

        function getIngredientTruck() {
            const table = document.getElementById("ingredients");
            const request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    //console.log(request.responseText);
                    table.innerHTML = request.responseText;
                }
            };

            request.open('GET', 'functions/getIngredientTruck.php');
            request.send();
        }

        function disableIngredient(ingredient) {
            const request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    if (request.responseText !== "") {
                        alert(request.responseText);

                    }
                }
            };

            request.open('POST', 'functions/disableIngredient.php');
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.send('ingredient=' + ingredient);
            getIngredientTruck();
        }

        function addIngredient() {
            let checkbox = document.getElementsByName("checkbox");
            const deleteMe = document.getElementById("deleteMe");
            const selectDiv = document.getElementById("selectDiv");
            console.log(checkbox[0].checked);
            if (!checkbox[0].checked) {
                const child = document.createElement('div');
                const input1 = document.createElement('input');
                input1.type = "text";
                input1.id = "disabledTextInput";
                input1.name = "newIngredient";
                input1.setAttribute("required", "required");
                input1.className = "form-control mt-3";
                input1.placeholder = "Nom de l'ingrédient";
                child.appendChild(input1);
                const div1 = document.createElement('div');
                div1.className = "custom-file mt-3";
                const input2 = document.createElement('input');
                input2.type = "file";
                input2.className = "custom-file-input";
                input2.id = "validatedCustomFile";
                input2.name = "ingredientImg";
                input2.setAttribute("required", "required");
                div1.appendChild(input2);
                const label2 = document.createElement('label');
                label2.className = "custom-file-label";
                label2.setAttribute("for", "validatedCustomFile");
                label2.innerText = "Choisir une image...";
                div1.appendChild(label2);
                child.appendChild(div1);
                deleteMe.appendChild(child);
                while (selectDiv.firstChild) {
                    selectDiv.removeChild(selectDiv.firstChild);
                }
            } else {
                while (deleteMe.firstChild) {
                    deleteMe.removeChild(deleteMe.firstChild);
                }
                if (!selectDiv.firstChild) {
                    const label1 = document.createElement('label');
                    label1.setAttribute("for", "selectIngredientName");
                    label1.id = "selectName";
                    label1.innerText = "Nom";
                    selectDiv.appendChild(label1);
                    const select1 = document.createElement("select");
                    select1.className = "form-control";
                    select1.id = "selectIngredientName";
                    selectDiv.appendChild(select1);
                    showCategory();
                }
            }
        }
        function addInBdd() {
            const ingredient = document.getElementById('selectIngredientName');
            const category = document.getElementById('selectCategory');
            const request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    if (request.responseText !== "") {
                        alert(request.responseText);

                    }
                }
            };
            request.open('POST', 'functions/addInBdd.php');
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.send('ingredient=' + ingredient + '&category=' + category);
            getIngredientTruck();
        }
        function showCategory() {
            const select = document.getElementById("selectCategory");
            const name = document.getElementById("selectName");
            if (select.value !== "Choisir une catégorie.." && name !== null) {
                name.innerText = select.value;
                if (select[0].value === "Choisir une catégorie..") {
                    select.removeChild(select[0]);
                }

            }
            const request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    if (request.responseText !== "") {
                        let myjson = JSON.parse(request.responseText);
                        const selectName = document.getElementById("selectIngredientName");
                        while (selectName.firstChild) {
                            selectName.removeChild(selectName.firstChild);
                        }
                        for (let i = 0; i < myjson.length; i++) {
                            const option = document.createElement("option");
                            option.value = myjson[i]["ingredientName"];
                            option.innerText = myjson[i]["ingredientName"];
                            selectName.appendChild(option);
                        }

                    }
                }
            };
            request.open('POST', 'functions/selectIngredient.php');
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            request.send('ingredient=' + select.value);
        }
        window.onload = getIngredientTruck;
    </script>
<?php include "footer.php"; ?>