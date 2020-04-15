<?php
session_start(); 
require 'conf.inc.php';
require 'functions.php';
include 'header.php';







	$pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory FROM INGREDIENTS");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

   /*foreach ($result as $value) {
        echo "<option value='" . $value["ingredientName"] . "'>" . $value["ingredientImage"] . "</option>";
    }*/

    echo "<pre>";
	print_r($result);
	echo "</pre>";
                  






 include "footer.php";