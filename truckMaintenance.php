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
    <?php
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idTruck FROM USER, TRUCK WHERE emailAddress = :email AND idUser = user");
    $queryPrepared->execute([
        ":email" => $_SESSION["email"]
    ]);
    $truck = $queryPrepared->fetch(PDO::FETCH_ASSOC);

    if (!empty($truck)) {
        $truck = $truck["idTruck"];
        $queryPrepared = $pdo->prepare("SELECT idMaintenance, maintenanceName, DATE_FORMAT(maintenanceDate, '%d/%m/%Y') as maintenanceDate, maintenancePrice, km FROM MAINTENANCE WHERE truck = :truck ORDER BY DATE(maintenanceDate) DESC");
        $queryPrepared->execute([
            ":truck" => $truck
        ]);
        $maintenance = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    }
    $truck = 1;
    $queryPrepared = $pdo->prepare("SELECT idMaintenance, maintenanceName, DATE_FORMAT(maintenanceDate, '%d/%m/%Y') as maintenanceDate, maintenancePrice, km FROM MAINTENANCE WHERE truck = :truck ORDER BY DATE(maintenanceDate) DESC");
    $queryPrepared->execute([
        ":truck" => $truck
    ]);
    $maintenance = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    if (count($maintenance) > 0) {
    ?>

    <div class="col-md-4">
        <?php
        $queryPrepared = $pdo->prepare("SELECT status FROM pa2a2drivncook.TRUCKSTATUS WHERE truck = :truck AND status NOT IN (14)");
        $queryPrepared->execute([
            ":truck" => 1
        ]);
        $result = $queryPrepared->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            echo "<p class='text-success mb-5'><i class='fas fa-check'></i>&nbsp;Camion opérationnel</p>";
        } else {
            echo "<p class='text-danger mb-5'><i class='fas fa-times'></i>&nbsp;Camion Indisponible</p>";
            if ($result["status"] == 10) {
                echo "<button class='btn btn-dark mb-5 mr-5 ml-5' data-toggle='modal' data-target='#validModal'>Rendre le camion disponible</button>";
            }
        }
        ?>
    </div>
    <div class="col-md-5">
        <button class="btn btn-danger mb-5 mr-5 ml-5" data-toggle="modal" data-target="#incident">Enregistrer une
            maintenance/incident
        </button>
    </div>
    <div class="accordion" id="maintenanceCollapse">
        <?php
        for ($i = 0; $i < count($maintenance); $i++) { ?>
            <div class="card">
                <div class="card-header" id="<?php echo 'header' . $i ?>">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#<?php echo 'collapse' . ($i + 1) ?>" aria-expanded="true"
                                aria-controls="<?php echo 'collapse' . $i ?>">
                            <?php echo $maintenance[$i]["maintenanceName"] . " - " . $maintenance[$i]["maintenanceDate"] ?>
                        </button>
                    </h2>
                </div>
                <div id="<?php echo 'collapse' . ($i + 1) ?>" class="collapse"
                     aria-labelledby="<?php echo 'header' . ($i + 1) ?>" data-parent="#maintenanceCollapse">
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
                            Prix : <?php echo $maintenance[$i]["maintenancePrice"] ?> €
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
    </div>
    <div class="modal fade" id="incident" tabindex="-1" role="dialog" aria-labelledby="incidentModal"
         aria-hidden="true">
        <form method="POST" action="./functions/addIncident.php">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addIncident">Modification d'un entrepôt</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group flex-nowrap mt-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="incidentTitle">Titre</span>
                            </div>
                            <input type="text" class="form-control" id="incidentName" name="incidentName"
                                   placeholder="Nom de la maintenance" required>
                        </div>
                        <div class="input-group flex-nowrap mt-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="Date">Date</span>
                            </div>
                            <input type="date" class="form-control" id="incidentDate" name="incidentDate"
                                   placeholder="Date de l'incident" required>
                        </div>
                        <div class="input-group flex-nowrap mt-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="km">Kilométrage</span>
                            </div>
                            <input type="number" class="form-control" id="incidentKM" name="incidentKM"
                                   placeholder="Kilométrage du camion" required>
                        </div>
                        <div class="input-group flex-nowrap mt-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="price">Prix</span>
                            </div>
                            <input type="number" id="incidentPrice" class="form-control" name="incidentPrice"
                                   placeholder="Prix payé" required>
                        </div>
                        <div class="custom-control custom-switch mt-1">
                            <input type="checkbox" class="custom-control-input" id="petrificusTotalus"
                                   name="petrificusTotalus">
                            <label class="custom-control-label" for="petrificusTotalus">Camion toujours immobilisé
                                ?</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Enregistrer</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="validModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Etes-vous sur de rendre le camion de nouveau disponible ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                    <a role="button" href="./functions/makeTruckAvailable.php?truck=<?php echo $truck ?>"
                       class="btn btn-primary">Oui</a>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php" ?>
</body>