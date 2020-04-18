<?php 
session_start(); 
require 'conf.inc.php';
require 'functions.php';


for($i = 1 ; $i <= count($_POST); $i++){
	if($_POST[$i] != 0 ){

		$pdo = connectDB();
	    $queryPrepared = $pdo->prepare("SELECT * FROM CARTINGREDIENT WHERE cart = :cart AND ingredient = :ingredient");
	    $queryPrepared->execute([
	    	":cart" => 1,
	    	":ingredient" => $i
	   	]);
	    $result = $queryPrepared->fetch();

	  	if(empty($result)){

	  		$pdo = connectDB();
		    $queryPrepared = $pdo->prepare("INSERT INTO CARTINGREDIENT (cart, ingredient, quantity) VALUES (:cart, :ingredient, :quantity)");
		    $queryPrepared->execute([
		    	":cart" => 1,
		    	":ingredient" => $i,
		    	":quantity" => $_POST[$i]
		   	]);
	  	}else{
	  		$pdo = connectDB();
		    $queryPrepared = $pdo->prepare("UPDATE CARTINGREDIENT SET quantity = quantity + :quantity WHERE cart = :cart AND ingredient = :ingredient");
		    $queryPrepared->execute([
		    	":cart" => 1,
		    	":ingredient" => $i,
		    	":quantity" => $_POST[$i]
		   	]);
	  	}
	}

}

header("Location: cart.php");

 




