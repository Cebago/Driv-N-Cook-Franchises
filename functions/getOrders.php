<?php
session_start();
require "../conf.inc.php";
require "../functions.php";
//header("Content-Type: application/json");

$pdo = connectDB();
$truck = getMyTruck($_SESSION["email"]);
$queryPrepared = $pdo->prepare("SELECT idOrder, statusName, DATE_FORMAT(orderDate, '%H:%i:%s') as orderDate, cart 
                                            FROM ORDERS, ORDERSTATUS, STATUS 
                                            WHERE idOrder = orders 
                                              AND idStatus = status 
                                              AND orderType = 'Commande client' 
                                              AND truck = :truck 
                                              AND idStatus != 4
                                              ORDER BY orderDate DESC");
$queryPrepared->execute([
    ":truck" => $truck
]);
$orders = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
for ($i = 0; $i < count($orders); $i++) {
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