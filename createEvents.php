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

    <?php if(!empty($info)){
        $dir    = 'img/eventsPics/';
        $images = array_diff(scandir($dir), array('..', '.'));
        ?>
        <button onclick="getCurrentSlide('#carouselExampleInterval')"> GiveMe Slide</button>
        <div class="col-md-4 display-1" style="margin-left: auto; margin-right: auto">
            <div id="carouselExampleInterval" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $key => $image){
                        ?>
                    <div class="carousel-item <?php echo $key==2?'active':''; ?>" data-interval="false">
                        <img src="<?php echo $dir.$image ?>" class="d-block w-100" alt="...">
                    </div>
                   <?php } ?>

                </div>
                <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>


    <form class="col-md-11 mx-auto mt-5 card pb-2 pt-2" method="POST" action="functions/newEvent.php">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">Nom du camion</span>
            </div>
            <input type="text" class="form-control" name="truckName" aria-label="truckName" aria-describedby="basic-addon1" value="<?php echo $truck; ?>" readonly>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="beginDate">Date de début</label>
                <input type="date" class="form-control" id="beginDate" name="beginDate" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="endDate">Date de fin</label>
                <input type="date" class="form-control" id="endDate" name="endDate" min="<?php echo date('Y-m-d'); ?>" required>
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
        <div class="form-group">
            <label for="inputAddress">Adresse de l'évènement</label>
            <input type="text" class="form-control" id="inputAddress" name="address" placeholder="ex: 242 av. du Faubourg Saint-Antoine" required>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">Ville</label>
                <input type="text" class="form-control" id="inputCity" name="city" placeholder="Bayonne" required>
            </div>
            <div class="form-group col-md-2">
                <label for="inputZip">Code postal</label>
                <input type="text" class="form-control" id="inputZip" name="zip" placeholder="64100" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Créer l'évènement</button>
    </form>


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
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#useOne" role="tab"
                               aria-controls="home" aria-selected="true"><i class="fas fa-photo-video"></i>&nbsp;Bibliothèque
                                d'images</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#uploadOne" role="tab"
                               aria-controls="profile" aria-selected="false"><i class="fas fa-upload"></i>&nbsp;Uploader
                                une nouvelle image</a>
                        </li>
                    </ul>
                    <div class="tab-content card mt-1" id="myTabContent">
                        <div class="tab-pane fade show active" id="useOne" role="tabpanel"
                             aria-labelledby="home-tab">
                        </div>
                        <div class="tab-pane fade" id="uploadOne" role="tabpanel" aria-labelledby="profile-tab">
                            <form method="POST" enctype="multipart/form-data"
                                  onsubmit="return uploadToNewsletter(event)">
                                <div class="input-group mb-3 mt-5 ml-2 mr-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="title">Titre de l'image</span>
                                    </div>
                                    <input type="text" class="form-control mr-4" name="imageTitle" id="imageTitle"
                                           placeholder="Titre de l'image" aria-label="Titre de l'image"
                                           aria-describedby="title" required>
                                </div>
                                <div class="custom-file mb-2 ml-2 mr-2">
                                    <input type="file" class="custom-file-input" id="uploadImage" name="uploadImage"
                                           required>
                                    <label class="custom-file-label mr-4" for="uploadImage">Choisir une
                                        image</label>
                                </div>
                                <button type="submit" class="btn btn-primary mb-3 ml-2 mr-2">Uploader l'image
                                </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

    <?php } else{?>

        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Oups, vous n'avez pas de camion</h1>
                <p class="lead">Il vous faut un camion pour pouvoir créer un évennement !</p>
            </div>
        </div>

    <?php } ?>

    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}


?>


