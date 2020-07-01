<?php
require_once "conf.inc.php";

/**
 * @return PDO
 */
function connectDB(){
    try{
        $pdo = new PDO(DBDRIVER.":host=".DBHOST.";dbname=".DBNAME ,DBUSER,DBPWD);
        //Permet d'afficher les erreurs SQL
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
    return $pdo;
}

/**
 * @param $email
 * @return false|string
 */
function createToken($email)
{
    $token = md5($email . "€monTokenDrivNCook£" . time() . uniqid());
    $token = substr($token, 0, rand(15, 20));
    return $token;
}

/**
 * @param $idCart
 * @return array
 */
function getIngredients($idCart)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idIngredient, ingredientName, ingredientImage, ingredientCategory, quantity FROM INGREDIENTS, CARTINGREDIENT, CART WHERE cart = idCart AND ingredient = idIngredient AND idCart = :cart ");
    $queryPrepared->execute([":cart" => $idCart]);
    return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

}

/**
 * @param $email
 */
function login($email)
{
    $token = createToken($email);
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE USERTOKEN, USER SET USERTOKEN.token = :token WHERE user = idUser AND emailAddress = :email AND tokenType = 'Site' ;");
    $queryPrepared->execute([":token" => $token, ":email" => $email]);
    $_SESSION["token"] = $token;
    $_SESSION["email"] = $email;
}


/**
 * @return bool
 */
function isActivated()
{
    if (!empty($_SESSION["email"]) && !empty($_SESSION["token"])) {
        $email = $_SESSION["email"];
        $token = $_SESSION["token"];
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT isActivated FROM USER, USERTOKEN WHERE emailAddress = :email 
                                          AND USERTOKEN.token = :token 
                                          AND user = idUser 
                                          AND tokenType = 'Site'");
        $queryPrepared->execute([
            ":email" => $email,
            ":token" => $token
        ]);
        $isActivated = $queryPrepared->fetch();
        $isActivated = $isActivated["isActivated"];
        if ($isActivated == 1) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * @return bool
 */
function isConnected()
{
    if (!empty($_SESSION["email"])
        && !empty($_SESSION["token"])) {
        $email = $_SESSION["email"];
        $token = $_SESSION["token"];
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT idUser FROM USER, USERTOKEN WHERE emailAddress = :email 
                                     AND USERTOKEN.token = :token 
                                     AND user = idUser 
                                     AND tokenType = 'Site'");
        $queryPrepared->execute([
            ":email" => $email,
            ":token" => $token
        ]);
        if (!empty($queryPrepared->fetch())) {
            login($email);
            return true;
        }
    }
    session_destroy();
    return false;
}

/**
 * @return bool
 */
function isAdmin()
{
    if (!empty($_SESSION["email"]) && !empty($_SESSION["token"])) {
        $email = $_SESSION["email"];
        $token = $_SESSION["token"];
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT roleName FROM USER, SITEROLE, USERTOKEN WHERE emailAddress = :email 
                                                 AND USERTOKEN.token = :token 
                                                 AND user = idUser 
                                                 AND userRole = idRole
                                                 AND tokenType = 'Site'");
        $queryPrepared->execute([
            ":email" => $email,
            ":token" => $token
        ]);
        $isAdmin = $queryPrepared->fetch();
        $isAdmin = $isAdmin["roleName"];
        if ($isAdmin == "Administrateur") {
            return true;
        }
        return false;
    }
}

/**
 * @return bool
 */
function isFranchisee()
{
    if (!empty($_SESSION["email"]) && !empty($_SESSION["token"])) {
        $email = $_SESSION["email"];
        $token = $_SESSION["token"];
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT roleName FROM USER, SITEROLE, USERTOKEN WHERE emailAddress = :email 
                                                 AND USERTOKEN.token = :token 
                                                 AND user = idUser 
                                                 AND userRole = idRole
                                                 AND tokenType = 'Site'");
        $queryPrepared->execute([
            ":email" => $email,
            ":token" => $token
        ]);
        $isAdmin = $queryPrepared->fetch();
        $isAdmin = $isAdmin["roleName"];
        if ($isAdmin == "Franchisé") {
            return true;
        }
        return false;
    }
}

/**
 * @param $email
 */
function logout($email)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("UPDATE USER, USERTOKEN SET USERTOKEN.token = null WHERE emailAddress = :email 
                                                    AND idUser = user 
                                                    AND tokenType = 'Site'");
    $queryPrepared->execute([":email" => $email]);
}

/**
 * @param $email
 * @return mixed
 */
function lastCart($email)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idCart FROM USER, CART WHERE user = idUser AND emailAddress = :email ORDER BY idCart DESC LIMIT 1");
    $queryPrepared->execute([":email" => $email]);
    $idCart = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    return $idCart["idCart"];
}

/**
 * @param $idTruck
 * @return mixed
 */
function truckWarehouse($idTruck)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idWarehouse FROM TRUCKWAREHOUSE, TRUCK, WAREHOUSES WHERE idTruck = truck AND warehouse = idWarehouse AND warehouseType = 'Camion' AND idTruck = :truck");
    $queryPrepared->execute([":truck" => $idTruck]);
    $warehouse = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    return $warehouse["idWarehouse"];
}
