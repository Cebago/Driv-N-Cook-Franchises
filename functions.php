<?php
require_once "conf.inc.php";

/**
 * @return PDO
 */
function connectDB()
{
    try {
        $pdo = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
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
        }
    }
    return false;
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
    }
    return false;
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

/**
 * @param $email
 * @return mixed|null
 */
function getMyTruck($email)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idTruck FROM TRUCK, USER WHERE idUser = user AND emailAddress = :email");
    $queryPrepared->execute([":email" => $email]);
    $truck = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if (empty($truck)) {
        return null;
    }
    return $truck["idTruck"];
}

/**
 * @param $email
 * @return array
 */
function getMessages($email)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT firstname, lastname, emailAddress, contactSubject, DATE_FORMAT(CONTACT.createDate, '%d/%m/%Y') as createDate, isRead,idContact, contactDescription, receiver FROM USER, CONTACT WHERE CONTACT.user = idUser AND receiver = (SELECT idTruck FROM TRUCK, USER WHERE user = idUser AND emailAddress = :email)");
    $queryPrepared->execute([":email" => $email]);
    return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

}

/**
 * @param $cart
 * @return mixed|null
 */
function allMenuFromCart($cart)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idMenu, quantity, menuName FROM CARTMENU, MENUS WHERE cart = :cart AND menu = idMenu");
    $queryPrepared->execute([":cart" => $cart]);
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        return null;
    }
    return $result;
}

/**
 * @param $cart
 * @return mixed|null
 */
function allProductFromCart($cart)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idProduct, quantity, productName FROM CARTPRODUCT, PRODUCTS WHERE cart = :cart AND product = idProduct");
    $queryPrepared->execute([":cart" => $cart]);
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        return null;
    }
    return $result;
}

/**
 * @param $menu
 * @return array|null
 */
function allProductFromMenu($menu)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idProduct, productName FROM SOLDIN, PRODUCTS WHERE menu = :menu AND product = idProduct");
    $queryPrepared->execute([":menu" => $menu]);
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        return null;
    }
    return $result;
}

/**
 * @param $idOrder
 * @return array|null
 */
function statusOfOrder($idOrder)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT statusName, statusDescription FROM STATUS, ORDERSTATUS WHERE status = idStatus AND statusType = 'Commande' AND orders = :order");
    $queryPrepared->execute([":order" => $idOrder]);
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        return null;
    }
    return $result;
}

/**
 * @param $product
 * @return array|null
 */
function allIngredientsFromProduct($product)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT ingredientName FROM INGREDIENTS, COMPOSE WHERE ingredient = idIngredient AND product = :product");
    $queryPrepared->execute([":product" => $product]);
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        return null;
    }
    return $result;
}

/**
 * @param $idTruck
 * @return bool
 */
function isOpen($idTruck)
{
    $translateDay = [
        1 => "Lundi",
        2 => "Mardi",
        3 => "Mercredi",
        4 => "Jeudi",
        5 => "Vendredi",
        6 => "Samedi",
        7 => "Dimanche",
    ];
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT * FROM OPENDAYS WHERE openDay = :currentDay AND startHour < current_time() AND endHour > current_time() AND truck = :idTruck;");
    $queryPrepared->execute([":currentDay" => $translateDay[date("N")], ":idTruck" => $idTruck]);
    return !empty($queryPrepared->fetch());
}

/**
 * @param $idTruck
 * @return array
 */
function getMyMenus($idTruck)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT * FROM MENUS WHERE truck = :idTruck");
    $queryPrepared->execute([
        ":idTruck" => $idTruck
    ]);
    return $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param $idTruck
 * @return array
 */
function getMyProducts($idTruck)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT * FROM MENUS WHERE truck = :idTruck");
    $queryPrepared->execute([
        ":idTruck" => $idTruck
    ]);
    $result = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    if ($result == null) {
        return null;
    }
    return $result;
}

/**
 * @param $idCategory
 * @return mixed|null
 */
function categoryOfProduct($idCategory)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT categoryName FROM PRODUCTCATEGORY WHERE idCategory = :category");
    $queryPrepared->execute([
        ":category" => $idCategory
    ]);
   $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
   if ($result == null) {
       return null;
   }
   return $result;
}

/**
 * @param $idProduct
 * @return mixed|null
 */
function statusOfProduct($idProduct)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT status FROM PRODUCTSTATUS WHERE product = :product");
    $queryPrepared->execute([
        ":product" => $idProduct
    ]);
    $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if ($result == null) {
        return null;
    }
    return $result;
}

/**
 * @param $idMenu
 * @return mixed|null
 */
function statusOfMenus($idMenu)
{
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT status FROM MENUSSTATUS WHERE menus = :menu");
    $queryPrepared->execute([
        ":menu" => $idMenu
    ]);
    $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if ($result == null) {
        return null;
    }
    return $result;
}

/**
 * @return bool
 */
function isClient()
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
        $role = $queryPrepared->fetch();
        $role = $role["roleName"];
        if ($role == "Client") {
            return true;
        }
    }
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
    }
    return false;
}