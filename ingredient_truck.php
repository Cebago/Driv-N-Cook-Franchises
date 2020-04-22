<?php
session_start(); 
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

$pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientCategory FROM INGREDIENTS GROUP BY ingredientCategory");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

?>

<h1>Mes ingrédients</h1>

<table class="table w-75 ml-5 mt-5">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Ingrédient</th>
      <th scope="col">Famille</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody id="ingredients"></tbody>
</table>

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
		    <label for="selectCategory">Catégorie</label>
		    <select class="form-control" onchange="showCategory()" id="selectCategory">
		      <option selected>Choisir une catégorie..</option>
		      <?php foreach ($result as $value) {
		      	echo "<option value='".$value["ingredientCategory"]."'>".$value["ingredientCategory"]."</option>";
		      } ?>
		      
		    </select>
		  </div>
          <div class="form-group">
		    <label for="selectIngredientName" id="selectName">Nom</label>
		    <select class="form-control" id="selectIngredientName">
		    </select>
		  </div>
		  <?php $count = 0; ?>
		  <div class="custom-control custom-checkbox">
			  <input type="checkbox" class="custom-control-input" id="customCheck1">
			  <label class="custom-control-label" for="customCheck1" onclick="availableIngredient()">Mon ingredient n'existe pas</label>
		  </div>
		  <div class="form-group" id="deleteMe">
			  <div id="availableIngredient">

			  </div>
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

<script type="text/javascript">

	function getIngredientTruck(){
		const table = document.getElementById("ingredients");
		const request = new XMLHttpRequest();
		request.onreadystatechange = function(){
			if (request.readyState === 4 && request.status === 200) {
				//console.log(request.responseText);
				table.innerHTML = request.responseText;
			}
		}

		request.open('GET','functions/getIngredientTruck.php');
		request.send();
	}

	function disableIngredient(ingredient){
		const request = new XMLHttpRequest();
		request.onreadystatechange = function(){
			if (request.readyState === 4 && request.status === 200) {
				if(request.responseText !== ""){
					alert(request.responseText);

				}
			}
		}

		request.open('POST','functions/disableIngredient.php');
		request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		request.send('ingredient='+ingredient);
		getIngredientTruck();
	}

var count =0;

	function availableIngredient(){
		
		console.log(count);

		//let count = document.getElementByName(count);
		const available = document.getElementById("availableIngredient");
		const deleteMe = document.getElementById("deleteMe");
		if(count % 2){

				deleteMe.removeChild(available);
		
		}else{
			available.innerHTML = '<label for="disabledTextInput" id="todelete"></label><input type="text" id="disabledTextInput" class="form-control" placeholder="Nom"></div><div class="custom-file"><input type="file" class="custom-file-input" id="validatedCustomFile" required><label class="custom-file-label" for="validatedCustomFile">Choisir une image...</label><div class="invalid-feedback">Example invalid custom file feedback</div>';
		}
		count += 1;
		console.log(count);
	}

	function showCategory(){
		//afficher les catégories
		const select = document.getElementById("selectCategory");
		const name = document.getElementById("selectName");
		if(select.value !== "Choisir une catégorie.."){
			name.innerText = select.value;
			if(select[0].value === "Choisir une catégorie.."){
				select.removeChild(select[0]);
			}
			
		}	

		const request = new XMLHttpRequest();
		request.onreadystatechange = function(){
			if (request.readyState === 4 && request.status === 200) {
				if(request.responseText !== ""){
					let myjson = JSON.parse(request.responseText);
					const selectName = document.getElementById("selectIngredientName");
					while (selectName.firstChild) {
					  selectName.removeChild(selectName.firstChild);
					}
					for(let i = 0 ; i < myjson.length ; i++){
						const option = document.createElement("option");
						option.value = myjson[i]["ingredientName"];
						option.innerText = myjson[i]["ingredientName"];
						selectName.appendChild(option);
					}
					
				}
			}
		}

		request.open('POST','functions/selectIngredient.php');
		request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		request.send('ingredient='+select.value);
	
	}


	window.onload = getIngredientTruck;

</script>

<?php include "footer.php"; ?>