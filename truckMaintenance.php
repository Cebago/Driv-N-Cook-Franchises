<?php
session_start();
require "conf.inc.php";
require "functions.php";
include "header.php";
?>
</head>
<body>
<?php include "navbar.php" ?>
<h3 class=" mt-5 mb-5 mx-auto col-md-2">Maintenances du camion</h3>
<div class="col-md-11 mx-auto">
    <div class="accordion" id="maintenanceCollapse">
        <?php
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT idTruck FROM USER, TRUCK WHERE emailAddress = :email AND idUser = user");
        $queryPrepared->execute([
            ":email" => $_SESSION["email"]
        ]);
        $truck = $queryPrepared->fetch(PDO::FETCH_ASSOC);
        $truck = $truck["idTruck"];
        $queryPrepared = $pdo->prepare("SELECT idMaintenance, maintenanceName, DATE_FORMAT(maintenanceDate, '%d/%m/%Y') as maintenanceDate, maintenancePrice, km FROM MAINTENANCE WHERE truck = :truck");
        $queryPrepared->execute([
            ":truck" => 1
        ]);
        $maintenance = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
        if (count($maintenance) > 0) {
            for ($i = 0; $i < count($maintenance); $i++) { ?>
            <div class="card">
                <div class="card-header" id="<?php echo 'header' . $i ?>">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#<?php echo 'collapse' . ($i + 1) ?>" aria-expanded="true" aria-controls="<?php echo 'collapse' . $i ?>">
                            <?php echo $i + 1  . " - " . $maintenance[$i]["maintenanceName"]?>
                        </button>
                    </h2>
                </div>
                <div id="<?php echo 'collapse' . ($i + 1) ?>" class="collapse" aria-labelledby="<?php echo 'header' . ($i + 1) ?>" data-parent="#maintenanceCollapse">
                    <div class="card-body">
                        <p>
                            Numéro de maintenance : <?php echo $maintenance[$i]["idMaintenance"] ?>
                        </p>
                        <p>
                            Dénomination : <?php echo $maintenance[$i]["maintenanceName"] ?>
                        </p>
                        <p>
                            Date : <?php echo $maintenance[$i]["maintenanceDate"] ?>
                        </p>
                        <p>
                            Kilométrage du camion : <?php echo $maintenance[$i]["km"] ?>
                        </p>
                        <p>
                            Kilométrage du camion : <?php echo $maintenance[$i]["maintenancePrice"] ?> €
                        </p>
                    </div>
                </div>
            </div>
        <?php
            }
        } else {
            echo "<h4>Vous n'avez aucun incident enregistré avec votre camion</h4>";
        }
        ?>
    <?php include "footer.php" ?>
</div>
</body>