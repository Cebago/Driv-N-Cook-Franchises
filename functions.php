<?php
require_once "conf.inc.php";

function connectDB(){
    try{
        $pdo = new PDO(DBDRIVER.":host=".DBHOST.";dbname=".DBNAME ,DBUSER,DBPWD);
        //Permet d'afficher les erreurs SQL
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(Exception $e){
        die("Erreur SQL : ".$e->getMessage());
    }
    return $pdo;
}

function createToken($email){
    $token = md5($email."€monTokenDrivNCook£".time().uniqid());
    $token = substr($token, 0, rand(15, 20));
    return $token;
}

function login($email){
    //Création d'un token
    $token = createToken($email);
    //Insertion en BDD pour l'user qui a pour email $email
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE USER SET token = :token WHERE emailAddress = :email ");
    $queryPrepared->execute([":token"=>$token, ":email"=>$email]);
    //Insertion en session du token et de l'email
    $_SESSION["token"] = $token;
    $_SESSION["email"] = $email;
}