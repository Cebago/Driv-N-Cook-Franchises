<?php
include "header.php";
include "navbar.php";
?>
<div class="col-md-11 mx-auto">
    <h2 class="mt-2 mb-5">Consulter les informations de mon camion</h2>
    <div>
        <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="idTruck">Nom du camion</span>
            </div>
            <input type="text" id="truckName" class="form-control truck" name="truckName" placeholder="Nom" aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="input-group flex-nowrap mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text">Marque</span>
            </div>
            <input type="text" id="truckManufacturers" class="form-control truck" name="truckManufacturers" placeholder="Marque du camion" aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="input-group flex-nowrap mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text">Modèle</span>
            </div>
            <input type="text" id="truckModel" class="form-control truck" name="truckModel" placeholder="Modèle du camion" aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="input-group flex-nowrap mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text">Plaque d'immatriculation</span>
            </div>
            <input type="text" id="licensePlate" class="form-control truck" name="licensePlate" placeholder="Plaque d'immatriculation du camion" aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="input-group flex-nowrap mt-2 mb-5">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre de kilomètres</span>
            </div>
            <input type="number" id="km" class="form-control truck" name="km" placeholder="Kilomètres parcourus" aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div id="schedule">
        </div>
    </div>
</div>
<script>
    function getTruckInfo(user) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function(){
            if (request.readyState === 4 && request.status === 200) {
                let myJson = JSON.parse(request.responseText);
                const input = document.getElementsByClassName("truck");
                for (let i = 0; i < input.length; i++) {
                    input[i].value = (myJson[input[i].id]);

                }
                const calendar = document.getElementById("schedule");
                const inside = Object.keys(myJson["opendays"]);
                const table = document.createElement("table");
                table.className = "table table-stripped table-responsive";
                const thead = document.createElement("thead");
                thead.className = "thead-dark"
                const trh = document.createElement("tr");
                const th1 = document.createElement("th");
                th1.scope = "col";
                th1.innerText = "Jour";
                const th2 = document.createElement("th");
                th2.scope = "col";
                th2.innerText = "Heure d'ouverture";
                const th3 = document.createElement("th");
                th3.scope = "col";
                th3.innerText = "Heure de fermeture";
                trh.appendChild(th1);
                trh.appendChild(th2);
                trh.appendChild(th3);
                thead.appendChild(trh);
                table.appendChild(thead);
                const tbody = document.createElement("tbody");
                for (let i = 0; i < inside.length; i++) {
                        console.log(myJson["opendays"][inside[i]]["weekDay"]);
                }

                calendar.appendChild(table);
            }
        }
        request.open('GET','functions/myTruckInfo.php?user=' + user);
        request.send();
    }

    window.onload = function() {
        getTruckInfo(2);
    }
</script>
