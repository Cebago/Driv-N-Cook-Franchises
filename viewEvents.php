<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isConnected() && isActivated() && isFranchisee()) {
    $idTruck = getMyTruck($_SESSION["email"]);
    $pdo = connectDB();
    $queryPrepared = $pdo->prepare("SELECT idEvent, eventDesc, eventImg, eventType, eventName, eventAddress, eventCity, eventPostalCode, eventBeginDate, eventEndDate, eventStartHour, eventEndHour FROM EVENTS, HOST, TRUCK WHERE event = idEvent AND truck = idTruck AND truck = :idTruck");
    $queryPrepared->execute([
        ":idTruck" => $idTruck
    ]);
    ;
    $info = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

    include "header.php";
    ?>

    <?php include "navbar.php";
    if(empty($info)){?>

        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">Aucun événement de programmé!</h1>
                <p class="lead">N'hésitez pas à privatiser votre camion</p>
                <button class="btn btn-success" onclick="window.location.href='createEvents.php'">Créer un évennement !</button>
            </div>
        </div>
    <?php }else {  ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Type d'évennement</th>
                            <th scope="col">Nom de l'evennement</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                            foreach ($info as $event){
                        ?>
                            <tr onclick="showDetails('details<?php echo $event["idEvent"] ?>')">
                                <td><?php echo $event["idEvent"] ?></td>
                                <td><?php echo $event["eventType"] ?></td>
                                <td><?php echo $event["eventName"] ?></td>
                            </tr>


                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            foreach ($info as $key => $event) {
            ?>

            <div class="card cardDetails" id="details<?php echo $event["idEvent"]?>" style="width: 50%; display: <?php echo $key?"none":"block" //j'affiche le premier element uniquement?>">
                <img src="<?php echo $event["eventImg"]?> "style="width: 100%" class="card-img-top" alt="" >
                <div class="card-body">
                    <h5 class="card-title"><?php echo $event["eventName"] ?></h5>
                    <p class="card-text"><b><?php echo $event["eventType"]?></b></p>
                    <hr>
                    <p class="card-text"><b><?php echo $event["eventDesc"]?></b></p>
                    <hr>
                </div>


            </div>
            <?php } ?>

        </div>
        <script src="scripts/scripts.js"></script>
    <?php }
    include "footer.php";
} else {
    header("Location: login.php");
}