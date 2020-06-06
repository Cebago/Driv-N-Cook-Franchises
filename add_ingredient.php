<?php

session_start(); 
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

$pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory, idIngredient FROM INGREDIENTS");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Ajou</h1>



<button type="button" class="btn btn-success" data-toggle="modal" data-target="#mymodal" data-whatever="@mdo">Open modal for @mdo</button>

<div class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="mymodal" aria-hidden="true">
  <div class="modal-dialog" role="document">
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
            <label for="recipient-name" class="col-form-label">Nom de l'aliment:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
		    <label for="exampleFormControlSelect1">Catégorie de l'ingrédient</label>
		    <select class="form-control" id="exampleFormControlSelect1">
		      <option>Fruit</option>
		      <option>Légume</option>
		      <option>Boisson</option>
		      <option>Féculent</option>
		      <option>Céréales</option>
		      <option>Produit laitier</option>
		      <option>Viande, poisson, oeufs</option>
		      <option>Corps gras</option>
		      <option>Sucre</option>
		    </select>
		  </div>
          <div class="custom-file">
		    <input type="file" class="custom-file-input" id="validatedCustomFile" required>
		    <label class="custom-file-label" for="validatedCustomFile">Choisir une image...</label>
		    <div class="invalid-feedback">Example invalid custom file feedback</div>
		  </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-success">Ajouter</button>
      </div>
    </div>
  </div>
</div>



<?php include "footer.php"; ?>

