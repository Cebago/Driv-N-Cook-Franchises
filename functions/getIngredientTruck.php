<?php
session_start(); 
require '../conf.inc.php';
require '../functions.php';

$pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientCategory, idIngredient 
                                                FROM INGREDIENTS, STORE, WAREHOUSES, TRUCKWAREHOUSE, TRUCK, USER 
                                                WHERE idIngredient = STORE.ingredient 
                                                  AND STORE.warehouse = idWarehouse 
                                                  AND STORE.available = TRUE 
                                                  AND TRUCKWAREHOUSE.warehouse = idWarehouse 
                                                  AND TRUCKWAREHOUSE.truck = idTruck 
                                                  AND TRUCK.user = idUser
                                                  AND warehouseType = 'Camion'
                                                  AND user = :user");
    $queryPrepared->execute([":user" => 2]);
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

    $string = "";
    

    foreach ($result as $value) {
     	$string .= "<tr>";
     	$string .= "<td>".$value['ingredientName']."</td>";
     	$string .= "<td>".$value['ingredientCategory']."</td>";
     	$string .= "<td>";
     	$string .= '<button type="button" class="btn btn-danger btn-sm" onclick="disableIngredient('.$value["idIngredient"].')">Rendre indisponible</button>';
     	$string .= " ";
     	$string .= '<button type="button" class="btn btn-success btn-sm data" data-toggle="modal" data-target="#mymodal" onclick="">Ajouter un ingrédient</button>';
     	$string .= "</td>";
     } 

     echo($string);


