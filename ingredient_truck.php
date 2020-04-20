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

<h1>Mes ingrédients</h1>

<table class="table w-75 ml-5 mt-5">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Ingrédient</th>
      <th scope="col">Famille</th>
      <th scope="col">Quantité</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>

<?php foreach ($result as $value) { ?>

  <tbody>
    <tr>
      <th scope="row">1</th>
      <td><?php echo $value["ingredientName"]?></td>
      <td><?php echo $value["ingredientCategory"]?></td>
      <td><?php echo $value["ingredientName"]?></td>
    </tr>
    <tr>

<?php } ?>