<?php
require 'conf.inc.php';
require 'functions.php';

$pdo = connectDB();
$queryPrepared = $pdo->prepare("SELECT (SELECT COUNT(idWarehouse) FROM WAREHOUSES) as warehouse, (SELECT COUNT(idTruck) FROM TRUCK) as truck, (SELECT COUNT(idUser) FROM USER, SITEROLE WHERE userRole= idRole AND roleName = 'Franchisé') as user");
$queryPrepared->execute();
$result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta lang="FR">
    <title>Activez votre compte Driv'N Cook</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="menu mt-5 card col-md-8 mx-auto">
    <h1 class="card-header">Activez votre compte Driv'N Cook</h1>
    <div class="card-body">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="http://127.0.0.1/Driv-N-Cook-Back/img/2.png" class="card-img" alt="Logo de Driv'N Cook">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Présentation</h5>
                        <p class="card-text">Driv'N Cook est une entreprise fictive de food-truck visant à permettre à des étudiants de valider leur projet de fin de seconde année d'étude.</p>
                        <p class="card-text">
                            L'entreprise est dirigée par Frédéric Sananes. Elle compte à ce jour <?php echo $result["warehouse"]; ?> entrepôts répartis partout en Île-de-France et avec une trentaine de salariés y travaillant.
                            Avec à ce jour <?php echo $result["truck"]; ?> camions conduits par leurs <?php echo $result["user"]; ?> franchisés, Driv'N Cook assure une présence à travers toute la région parisienne avec pour seul mot d'ordre: "Manger local !"
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="mt-2">Driv'N Cook est une entreprise de food-truck.</h2>
    </div>
</div>
</body>
</html>