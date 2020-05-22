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
    <div class="col-md-4">
        <p class="text-success">
            <i class="fas fa-check"></i>&nbsp;Camion opérationnel
        </p>
        <p class="text-danger">
            <i class="fas fa-times"></i>&nbsp;Camion Indisponible
        </p>
        <button class="btn btn-dark mb-5 mr-5 ml-5">Rendre le camion disponible</button>
    </div>
    <div class="col-md-5">
        <button class="btn btn-danger mb-5 mr-5 ml-5" data-toggle="modal" data-target="#incident">Enregistrer un incident</button>
        <button class="btn btn-warning mb-5 mr-5 ml-5">Enregistrer une maintenance</button>
    </div>
    <div class="accordion" id="maintenanceCollapse">
        <?php
        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("SELECT idTruck FROM USER, TRUCK WHERE emailAddress = :email AND idUser = user");
        $queryPrepared->execute([
            ":email" => $_SESSION["email"]
        ]);
        $truck = $queryPrepared->fetch(PDO::FETCH_ASSOC);
        $truck = $truck["idTruck"];
        $queryPrepared = $pdo->prepare("SELECT idMaintenance, maintenanceName, DATE_FORMAT(maintenanceDate, '%d/%m/%Y') as maintenanceDate, maintenancePrice, km FROM MAINTENANCE WHERE truck = :truck ORDER BY maintenanceDate DESC");
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
    </div>
    <div class="modal fade" id="incident" tabindex="-1" role="dialog" aria-labelledby="incidentModal" aria-hidden="true">
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
                            <input type="text" class="form-control" id="incidentName" name="incidentName" placeholder="Nom de la maintenance" required>
                        </div>
                        <div class="input-group flex-nowrap mt-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="Date">Date</span>
                            </div>
                            <input type="date" class="form-control" id="incidentDate" name="incidentDate" placeholder="Date de l'incident" required>
                        </div>
                        <div class="input-group flex-nowrap mt-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="km">Kilométrage</span>
                            </div>
                            <input type="number" class="form-control" id="incidentKM" name="incidentKM" placeholder="Kilométrage du camion" required>
                        </div>
                        <div class="input-group flex-nowrap mt-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="price">Prix</span>
                            </div>
                            <input type="number" id="incidentPrice" class="form-control" name="incidentPrice" placeholder="Prix payé" required>
                        </div>
                        <div class="custom-control custom-switch mt-1">
                            <input type="checkbox" class="custom-control-input" id="petrificusTotalus" name="petrificusTotalus">
                            <label class="custom-control-label" for="petrificusTotalus">Camion toujours immobilisé ?</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Enregistrer l'incident</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include "footer.php" ?>
</body>