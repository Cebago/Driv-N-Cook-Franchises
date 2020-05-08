<?php
session_start(); 
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

	
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory, quantity, idIngredient FROM INGREDIENTS, CARTINGREDIENT, CART, USER WHERE CARTINGREDIENT.ingredient = idIngredient AND CARTINGREDIENT.cart = idCart AND CART.user = idUser AND  user = 1");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);


    /*$queryPrepared2 = $pdo->prepare("SELECT ingredient, idIngredient, quantity FROM INGREDIENTS, CARTINGREDIENT  WHERE ingredient = idIngredient ");
    $queryPrepared2->execute();
    $result2 = $queryPrepared2->fetchAll(PDO::FETCH_ASSOC);*/


?>

<script type="text/javascript">

function addQuantity(count){
    let input = document.getElementsByName("quantity");
    input[count-1].value = parseInt(input[count-1].value,10)+1;
    
}

function deleteQuantity(count){
    let input = document.getElementsByName("quantity");
    if(parseInt(input[count-1].value,10) > 0){
        input[count-1].value = parseInt(input[count-1].value,10)-1;
    }
    
}

</script>

<div class="album py-5 bg-light">
    <div class="container">
      <div class="row">
        <?php
        $count = 0;
     foreach ($result as $value) { 
        $count++;
        ?>
	<div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title><?php echo $value["ingredientName"]?></title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em"><?php echo $value["ingredientImage"]; ?></text></svg>
            <div class="card-body">
              <p class="card-text"><?php echo $value["ingredientName"]; ?></p>
              <div class="d-flex justify-content-between align-items-center">
                  
                <button type="button" class="btn btn-sm btn-danger ml-1" onclick="deleteQuantity(<?php echo $count.",".$value["idIngredient"]; ?>)"><i class="fas fa-minus"></i></button> 
                  <input class="border ml-1 p-2 w-25" name="quantity" value="<?php echo $value["quantity"]; ?>" readonly>
                  <button type="button" onclick="addQuantity(<?php echo $count.",".$value["idIngredient"]; ?>)" class="btn btn-sm btn-success ml-1"><i class="fas fa-plus"></i></button>
                  
                <small class="text-muted" type="ml-5"><?php echo $value["ingredientCategory"];?></small>

            </div>
          </div>
        </div>	
        </div>	

<?php }?>
	    </div>
		</div>
	</div>

	
<!--
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
-->



  
<?php

  /* foreach ($result as $value) {
       echo "<pre>";
	print_r($value);
	echo "</pre>";
    }
*/
    
    
                  





 include "footer.php";
 ?>