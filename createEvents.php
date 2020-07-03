<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isConnected() && isActivated() && isFranchisee()) {
    include "header.php";
    ?>
    <body>
    <?php include "navbar.php"; ?>
    <form class="col-md-11 mx-auto mt-5 card pb-2 pt-2">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="beginDate">Date de début</label>
                <input type="date" class="form-control" id="beginDate" name="beginDate" min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="endDate">Date de fin</label>
                <input type="date" class="form-control" id="endDate" name="endDate" min="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="startHour">Heure de début</label>
                <input type="time" class="form-control" id="startHour" name="startHour" min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="endHour">Heure de fin</label>
                <input type="time" class="form-control" id="endHour" name="endHour" min="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputAddress">Adresse de l'évènement</label>
            <input type="text" class="form-control" id="inputAddress" name="address" placeholder="ex: 242 av. du Faubourg Saint-Antoine">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">Ville</label>
                <input type="text" class="form-control" id="inputCity" name="city">
            </div>
            <div class="form-group col-md-2">
                <label for="inputZip">Code postal</label>
                <input type="text" class="form-control" id="inputZip" name="zip">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>


    <?php
    include "footer.php";
} else {
    header("Location: login.php");
}