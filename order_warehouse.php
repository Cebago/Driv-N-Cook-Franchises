<?php
session_start(); 
require 'conf.inc.php';
require 'functions.php';
include 'header.php';

	
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory FROM INGREDIENTS");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);


    $queryPrepared2 = $pdo->prepare("SELECT quantity FROM CARTINGREDIENT");
    $queryPrepared2->execute();
    $result2 = $queryPrepared2->fetchAll(PDO::FETCH_ASSOC);
   

?>

	<?php

	 foreach ($result as $value) {?>

<div class="album py-5 bg-light">
    <div class="container">
      <div class="row">
	<div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em"><?php echo $value["ingredientImage"]; ?></text></svg>
            <div class="card-body">
              <p class="card-text"><?php echo $value["ingredientName"]; ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-success">Ajouter</button>
                  <button type="button" class="btn btn-sm btn-outline-warning"><?php foreach ($result2 as $value2) { echo $value2["quantity"]; }?></button>
                  <button type="button" class="btn btn-sm btn-outline-danger">Supprimer</button>
                </div>
                <small class="text-muted"><?php echo $value["ingredientCategory"];?></small>
              </div>
            </div>
          </div>
        </div>		
	
<?php }?>

	    </div>
		</div>
	</div>

  
<?php

  /* foreach ($result as $value) {
       echo "<pre>";
	print_r($value);
	echo "</pre>";
    }
*/
    
    
                  





 include "footer.php";
 ?>