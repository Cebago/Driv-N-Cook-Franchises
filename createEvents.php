<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isConnected() && isActivated() && isFranchisee()) {
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT truckName, idUser FROM USER, TRUCK WHERE idUser = user AND emailAddress = :email");
    $queryPrepared->execute([
        ":email" => $_SESSION["email"]
    ]);
    $info = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($info)) {
        $user = $info[0]["idUser"];
        $truck = $info[0]["truckName"];
    }
    include "header.php";
    ?>

    <?php include "navbar.php";
    if (isset($_SESSION["errors"])) {
        ?>
        <div class="col-md-11 mx-auto pb-2 pt-2 mt-5 alert-danger alert card">
            <ul>
                <?php
                foreach ($_SESSION["errors"] as $error) {
                    echo "<li>" . $error . "</li>";
                }
                ?>
            </ul>
        </div>
        <?php
        unset($_SESSION["errors"]);
    }
    ?>

    <?php if (!empty($info)) {
        ?>
        <form class="col-md-11 mx-auto mt-5 card pb-2 pt-2" method="POST" action="functions/newEvent.php">
            <div class="form-row">
                <div class="input-group col-md-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Nom du camion</span>
                    </div>
                    <input type="text" class="form-control" name="truckName" aria-label="truckName"
                           aria-describedby="basic-addon1" value="<?php echo $truck; ?>" readonly>
                </div>
                <div class="input-group col-md-6">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Nom de l'évennement</span>
                    </div>
                    <input type="text" class="form-control" name="eventName" aria-label="truckName"
                           aria-describedby="basic-addon1" placeholder="ex: Soirée Disco chez Boris">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="beginDate">Date de début</label>
                    <input type="date" class="form-control" id="beginDate" name="beginDate"
                           min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="endDate">Date de fin</label>
                    <input type="date" class="form-control" id="endDate" name="endDate"
                           min="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect01">Options</label>
                </div>
                <select class="custom-select" id="inputGroupSelect01" name="type" required>
                    <option selected value="">Choisir un type d'évènement...</option>
                    <option value="0">Réservation</option>
                    <option value="1">Dégustation</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="startHour">Heure de début</label>
                    <input type="time" class="form-control" id="startHour" name="startHour" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="endHour">Heure de fin</label>
                    <input type="time" class="form-control" id="endHour" name="endHour" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="inputAddress">Adresse de l'évènement</label>
                    <input type="text" class="form-control" id="inputAddress" name="address"
                           placeholder="ex: 242 av. du Faubourg Saint-Antoine" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="inputCity">Ville</label>
                    <input type="text" class="form-control" id="inputCity" name="city" placeholder="Bayonne" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="inputZip">Code postal</label>
                    <input type="text" class="form-control" id="inputZip" name="zip" placeholder="64100" required>
                </div>
            </div>
            <div class="form-group">
                <label for="eventDesc">Description de l'évènement</label>
                <textarea class="form-control" id="eventDesc" name="eventDesc" rows="3"></textarea>
            </div>
            <div class="form-group col-md-2" style="display: none">
                <label></label>
                <input type="text" class="form-control" id="inputImg" name="img">
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadImage"
                    onclick="chooseImage()">Continuer
            </button>

            <div class="modal fade" id="uploadImage" tabindex="-1" role="dialog" aria-labelledby="uploadImageLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadImageLabel">Ajouter une image</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mt-1">
                                <div class="tab-content card mt-1" id="myTabContent">
                                    <div class="tab-pane fade show active" id="useOne" role="tabpanel"
                                         aria-labelledby="home-tab">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary">Valider</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>


    <?php } else { ?>

        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Oups, vous n'avez pas de camion</h1>
                <p class="lead">Il vous faut un camion pour pouvoir créer un évennement !</p>
            </div>
        </div>

    <?php } ?>
    <script src="scripts/scripts.js"></script>
    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}


?>


