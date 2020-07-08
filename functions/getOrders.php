<?php
session_start();
require "../conf.inc.php";
require "../functions.php";
header("Content-Type: application/json");

$pdo = connectDB();
$truck = getMyTruck($_SESSION["email"]);
$queryPrepared = $pdo->prepare("SELECT idOrder, DATE_FORMAT(orderDate, '%H:%i:%s') as orderDate, orderPrice, cart, user
                                            FROM ORDERS 
                                            WHERE orderType = 'Commande client' 
                                              AND truck = :truck
                                              ORDER BY orderDate DESC");
$queryPrepared->execute([
    ":truck" => $truck
]);
$orders = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
for ($i = 0; $i < count($orders); $i++) {

    $queryPrepared = $pdo->prepare("SELECT lastname, firstname FROM USER WHERE idUser = :user");
    $queryPrepared->execute([":user" => $orders[$i]["user"]]);
    $user = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    $user = strtoupper($user["lastname"]) . " " . $user["firstname"];
    $orders[$i]["name"] = $user;

    if (statusOfOrder($orders[$i]["idOrder"]) != null) {
        $orders[$i]["status"] = [];
        $status = statusOfOrder($orders[$i]["idOrder"]);
        $orders[$i]["status"] = [];
        $orders[$i]["status"][] = statusOfOrder($orders[$i]["idOrder"]);
        /*foreach ($status as $statu) {
            $orders[$i]["status"][] = $statu["statusName"];
        }*/
    }
    if (allMenuFromCart($orders[$i]["cart"]) != null ) {
        $orders[$i]["menus"] = [];
        $menus = allMenuFromCart($orders[$i]["cart"]);
        foreach ($menus as $menu) {
            $productMenus = allProductFromMenu($menu["idMenu"]);
            $orders[$i]["menus"][$menu["menuName"]] = [];
            foreach ($productMenus as $productMenu) {
                $orders[$i]["menus"][$menu["menuName"]][] = $productMenu["productName"];
            }
        }
    }
    if (allProductFromCart($orders[$i]["cart"]) != null ) {
        $orders[$i]["products"] = [];
        $products = allProductFromCart($orders[$i]["cart"]);
        foreach ($products as $product) {
            $orders[$i]["products"][] = $product;
        }
    }
    $time = 0;
    $orderDate = DateTime::createFromFormat('H:i:s', $orders[$i]["orderDate"]);
    $newTime = DateTime::createFromFormat('H:i:s', date('H:i:s'));
    $time = $orderDate->diff($newTime);
    $time = $time->h . "h " .$time->i . "mins " . $time->s . "sec";
    $orders[$i]["time"] = $time;
}
echo json_encode($orders);