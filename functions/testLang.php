<?php
$jsonFilePath = '../../Driv-N-Cook-Client/assets/traduction.json';
$jsonFile = file_get_contents($jsonFilePath);
$jsonFile = json_decode($jsonFile, true);

// add data
$jsonFile["ingredients"] = array('Pomme' => array("en_EN" => "Apple", "es_ES" => "Pommas"));

$newJsonString = json_encode($jsonFile);
file_put_contents($jsonFilePath, $newJsonString);