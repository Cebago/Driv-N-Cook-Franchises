<?php
require_once "conf.inc.php";

function connectDB(){
    try{
        $pdo = new PDO(DBDRIVER.":host=".DBHOST.";dbname=".DBNAME ,DBUSER,DBPWD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(Exception $e){
        die("Erreur SQL : ".$e->getMessage());
    }
    return $pdo;
}

function getIngredient(){

    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName, ingredientImage, ingredientCategory FROM INGREDIENTS");
    $queryPrepared->execute();
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
   
}

function login($email){
    $token = createToken($email);
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE USER SET token = :token WHERE emailAddress = :email ");
    $queryPrepared->execute([":token"=>$token, ":email"=>$email]);
    $_SESSION["token"] = $token;
    $_SESSION["email"] = $email;
}
    
