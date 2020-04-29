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

function isConnected(){
    if(!empty($_SESSION["email"])
        && !empty($_SESSION["token"]) ){
        $email = $_SESSION["email"];
        $token = $_SESSION["token"];
        //Vérification d'un correspondant en base de données
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT idUser FROM USER WHERE emailAddress = :email AND token = :token");
        $queryPrepared->execute([
            ":email"=>$email,
            ":token"=>$token
        ]);
        if (!empty($queryPrepared->fetch()) ){
            login($email);
            return true;
        }
    }
    session_destroy();
    return false;
}

function isActivated(){
    if(!empty($_SESSION["email"]) && !empty($_SESSION["token"]) ){
        $email = $_SESSION["email"];
        $token = $_SESSION["token"];
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT isActivated FROM USER WHERE emailAddress = :email AND token = :token");
        $queryPrepared->execute([
            ":email"=>$email,
            ":token"=>$token
        ]);
        $isActivated = $queryPrepared->fetch();
        $isActivated = $isActivated["isActivated"];
        if ($isActivated == 1){
            return true;
        }else{
            return false;
        }
    }
}

function isAdmin(){
    if(!empty($_SESSION["email"]) && !empty($_SESSION["token"]) ){
        $email = $_SESSION["email"];
        $token = $_SESSION["token"];
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT roleName FROM USER, SITEROLE WHERE emailAddress = :email AND token = :token AND userRole = idRole");
        $queryPrepared->execute([
            ":email"=>$email,
            ":token"=>$token
        ]);
        $isAdmin = $queryPrepared->fetch();
        $isAdmin = $isAdmin["roleName"];
        if ($isAdmin == "Administrateur"){
            return true;
        }
        return false;
    }
}