<?php
session_start(); 
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

	
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory, cart, idUser, ingredient, idIngredient, quantity FROM INGREDIENTS, CARTINGREDIENT, USER WHERE cart = idUser AND ingredient = idIngredient");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);


    /*$queryPrepared2 = $pdo->prepare("SELECT ingredient, idIngredient, quantity FROM INGREDIENTS, CARTINGREDIENT  WHERE ingredient = idIngredient ");
    $queryPrepared2->execute();
    $result2 = $queryPrepared2->fetchAll(PDO::FETCH_ASSOC);*/


?>

	<?php

	 foreach ($result as $value) { 

	 	?>
<?php// foreach ($result2 as $value2) {?>

<div class="album py-5 bg-light">
    <div class="container">
      <div class="row">
	<div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title><?php echo $value["ingredientName"]?></title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em"><?php echo $value["ingredientImage"]; ?></text></svg>
            <div class="card-body">
              <p class="card-text"><?php echo $value["ingredientName"]; ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-success"><svg class="bi bi-plus" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  						<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
						  <path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
						</svg></button>
                  <button type="button" class="btn btn-sm btn-outline-warning" name="quantity"><?php echo $value["quantity"];?></button>
                  <button type="button" class="btn btn-sm btn-outline-danger"><svg class="bi bi-dash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					  <path fill-rule="evenodd" d="M3.5 8a.5.5 0 01.5-.5h8a.5.5 0 010 1H4a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
					</svg></button>
                </div>
                <small class="text-muted"><?php echo $value["ingredientCategory"];?></small>
              </div>
            </div>
          </div>
        </div>		


	    </div>
		</div>
	</div>

	<?php }?>

<script>
	function refreshTable() {
        const content = document.getElementById("tablebody");

        const request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if(request.readyState === 4) {
                if(request.status === 200) {
                    //console.log(request.responseText);
                    content.innerHTML = request.responseText;
                }
            }
        };
        request.open('GET', './functions/getTruckList.php', true);
        request.send();
    }
    
    function displayQuantity(quantity) {
        const quantity = document.getElementsByClassName("quantity");
        for (let i = 0; i < quantity.length; i++) {
            quantity[i].value = quantity;
        }
    }
    function deleteQuantity(quantity) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if(request.readyState === 4) {
                if(request.status === 200) {
                    //console.log(request.responseText);
                }
            }
        };
        request.open('POST', 'functions/unassignDriver.php');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(
            'truck=' + idtruck
        );
        refreshTable();
    }
    function addQuantity() {
        const truck = document.getElementById("assign").value;
        const user = document.getElementById("select").value;
        
        const request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if(request.readyState === 4) {
                if(request.status === 200) {
                    if (request.responseText !== "") {
                        alert(request.responseText);
                    }
                }
            }
        };
        request.open('POST', 'functions/assignDriver.php');
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(
            'user=' + user +
            "&truck=" + truck
        );
        refreshTable();
    }

    function getInfo(idtruck) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if(request.readyState === 4) {
                if(request.status === 200) {
                    let truckJson = JSON.parse(request.responseText);
                    const truck = document.getElementsByClassName("truck");
                    for (let i = 0; i < truck.length; i++) {
                        const input = document.getElementsByName(truck[i].name);
                        input[0].value = truckJson[0][truck[i].name];
                    }
                }
            }
        };
        request.open('GET', './functions/getTruckInfo.php?id='+idtruck, true);
        request.send();
    }
    
    function updateTruck() {
        const id = document.getElementById("update")
        const manufacturers = document.getElementById("updateManufacturers");
        const model = document.getElementById("updateModel");
        const license = document.getElementById("updateLicense");
        const km = document.getElementById("updateKM");
        
        const request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if(request.readyState === 4) {
                if(request.status === 200) {
                    if (request.responseText !== "") {
                        alert(request.responseText);
                    }
                }
            }
        };
        request.open('POST', './functions/updateTruck.php', true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        request.send(
            'id=' + id.value +
            '&manufacturers=' + manufacturers.value +
            '&model=' + model.value +
            '&license=' + license.value +
            '&km=' + km.value
        );
        refreshTable();
    }
    setInterval(refreshTable, 60000);
    window.onload = refreshTable;
</script>





  
<?php

  /* foreach ($result as $value) {
       echo "<pre>";
	print_r($value);
	echo "</pre>";
    }
*/
    
    
                  





 include "footer.php";
 ?>