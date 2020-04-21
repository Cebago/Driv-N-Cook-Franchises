<?php
session_start(); 
require 'conf.inc.php';
require 'functions.php';
include 'header.php';


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
				alert(request.responseText);

			}
		}

		request.open('POST','functions/disableIngredient.php');
		request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		request.send('ingredient='+ingredient);
	}

	window.onload = getIngredientTruck;

</script>