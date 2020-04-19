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
      <th scope="col">#</th>
      <th scope="col">Ingrédient</th>
      <th scope="col">Famille</th>
      <th scope="col">Quantité</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
