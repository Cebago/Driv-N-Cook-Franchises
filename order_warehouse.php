<?php
session_start(); 
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

    
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory FROM INGREDIENTS");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);


    /*$queryPrepared2 = $pdo->prepare("SELECT ingredient, idIngredient, quantity FROM INGREDIENTS, CARTINGREDIENT  WHERE ingredient = idIngredient ");
    $queryPrepared2->execute();
    $result2 = $queryPrepared2->fetchAll(PDO::FETCH_ASSOC);*/


?>

<script type="text/javascript">

function addQuantity(count){
    let input = document.getElementsByName("quantity");
    /*value[count].value = value[count].value + 1;
    console.log(value[count].value);*/
    console.dir(input[count-1].value+1);

}

</script>

    <?php
        $count = 0;
     foreach ($result as $value) { 
        $count++;
        ?>

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
                  <button type="button" onclick="addQuantity(<?php echo $count; ?>)" class="btn btn-sm btn-outline-success ml-1"><i class="fas fa-plus"></i></button>
                  <input class="border ml-1 p-2 w-25" name="quantity" value=0 readonly></input>
                  <button type="button" class="btn btn-sm btn-outline-danger ml-1"><i class="fas fa-minus"></i></button> 
                    <button type="button" class="btn btn-sm btn-secondary ml-5"> Ajouter</button>
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





  
<?php

  /* foreach ($result as $value) {
       echo "<pre>";
    print_r($value);
    echo "</pre>";
    }
*/
    
    
                  





 include "footer.php";
 ?>