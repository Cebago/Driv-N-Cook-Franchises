<?php
include "header.php";
include "navbar.php";
?>
<div class="col-md-11 mx-auto">
    <h2 class="mt-2 mb-5">Consulter les informations de mon camion</h2>
    <div id="allTruck" class="col-md-11 mx-auto">
        <div class="input-group flex-nowrap">
            <div class="input-group-prepend">
                <span class="input-group-text" id="idTruck">Nom du camion</span>
            </div>
            <input type="text" id="truckName" class="form-control truck" name="truckName" placeholder="Nom"
                   aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="input-group flex-nowrap mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text">Marque</span>
            </div>
            <input type="text" id="truckManufacturers" class="form-control truck" name="truckManufacturers"
                   placeholder="Marque du camion" aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="input-group flex-nowrap mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text">Modèle</span>
            </div>
            <input type="text" id="truckModel" class="form-control truck" name="truckModel"
                   placeholder="Modèle du camion" aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="input-group flex-nowrap mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text">Plaque d'immatriculation</span>
            </div>
            <input type="text" id="licensePlate" class="form-control truck" name="licensePlate"
                   placeholder="Plaque d'immatriculation du camion" aria-label="truckId"
                   aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="input-group flex-nowrap mt-2 mb-5">
            <div class="input-group-prepend">
                <span class="input-group-text">Nombre de kilomètres</span>
            </div>
            <input type="number" id="km" class="form-control truck" name="km" placeholder="Kilomètres parcourus"
                   aria-label="truckId" aria-describedby="addon-wrapping" readonly>
        </div>
        <div class="w-75">
            <div class="col-md-6 mx-auto" id="schedule"></div>
            <div class="mx-auto col-md-6">
                <button class="btn btn-dark" type="button" data-toggle="modal"
                        title="Modifier mes horaires d'ouvertures"
                        data-target="#hourModal" onclick="displayOpenDays(2); setTimeout(changeStatus, 500)">
                    Modifier mes horaires
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hourModal" tabindex="-1" role="dialog" aria-labelledby="hourModal" aria-hidden="true">
    <form method="POST" action="./functions/modifyHours.php">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifyHours">Modifier mes horaires</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $day = array(
                        "Lundi",
                        "Mardi",
                        "Mercredi",
                        "Jeudi",
                        "Vendredi",
                        "Samedi",
                        "Dimanche",

                    );
                    for ($i = 0; $i < count($day); $i++) {
                        ?>
                        <div class="mb-3">
                            <h5 class="modal-title"><?php echo $day[$i]; ?></h5>
                            <div id="parent<?php echo $day[$i]; ?>">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" onclick="changeStatus()"
                                           name="check<?php echo $day[$i] ?>" id="check<?php echo $day[$i] ?>">
                                    <label class="custom-control-label" for="check<?php echo $day[$i] ?>">Fermé</label>
                                </div>
                                <div class="input-group flex-nowrap mt-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Planning</span>
                                    </div>
                                    <input type="text" class="form-control startHour" id="input<?php echo $day[$i]; ?>"
                                           name="<?php echo $day[$i]; ?>" placeholder="Horaire d'ouverture"
                                           aria-describedby="addon-wrapping">
                                </div>
                                <small>Respectez le format XX:XX-XX:XX et séparez par un "/" les différentes
                                    plages</small>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button class="btn btn-success" type="submit">Modifier les
                        horaires
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function changeStatus() {
        const check = document.getElementsByClassName("custom-control-input");
        for (let i = 0; i < check.length; i++) {
            if (check[i].checked === true) {
                const parent = check[i].parentElement;
                const child = parent.children;
                child[1].innerText = "Ouvert";
            } else {
                const parent = check[i].parentElement;
                const child = parent.children;
                child[1].innerText = "Fermé";
            }
        }
    }

    function displayOpenDays(user) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                let myJson = JSON.parse(request.responseText);
                myJson = myJson["opendays"];
                for (let i = 0; i < myJson.length; i++) {
                    const day = document.getElementById("input"+myJson[i]["openDay"]);
                    day.value = "";
                }
                for (let i = 0; i < myJson.length; i++) {
                    const day = document.getElementById("input"+myJson[i]["openDay"]);
                    if (day.value === "") {
                        day.value = myJson[i]["startHour"] + " - " + myJson[i]["endHour"];
                    } else {
                        day.value += " / " + myJson[i]["startHour"] + " - " + myJson[i]["endHour"];
                    }
                    const check = document.getElementById("check" + myJson[i]["openDay"]);
                    check.checked = true;
                }
            }
        }
        request.open('GET', 'functions/myTruckInfo.php?user=' + user);
        request.send();
    }

    function getTruckInfo(user) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
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
                    const tr = document.createElement("tr");
                    const search = document.getElementById(myJson["opendays"][inside[i]]["openDay"]);
                    if (search === null) {
                        const thd = document.createElement("th");
                        thd.scope = "row";
                        thd.id = myJson["opendays"][inside[i]]["openDay"];
                        thd.innerText = myJson["opendays"][inside[i]]["openDay"];
                        thd.className = "text-center";
                        const td1 = document.createElement("td");
                        td1.innerText = myJson["opendays"][inside[i]]["startHour"];
                        td1.className = "text-center";
                        const td2 = document.createElement("td");
                        td2.className = "text-center";
                        td2.innerText = myJson["opendays"][inside[i]]["endHour"];
                        tr.appendChild(thd);
                        tr.appendChild(td1);
                        tr.appendChild(td2);
                    } else {
                        search.setAttribute("rowspan", "2");
                        search.className = "align-middle text-center";
                        const td1 = document.createElement("td");
                        td1.innerText = myJson["opendays"][inside[i]]["startHour"];
                        td1.className = "text-center";
                        const td2 = document.createElement("td");
                        td2.innerText = myJson["opendays"][inside[i]]["endHour"];
                        td2.className = "text-center";
                        tr.appendChild(td1);
                        tr.appendChild(td2);
                    }
                    tbody.appendChild(tr);
                    table.appendChild(tbody);
                    calendar.appendChild(table);
                }
            }
        }
        request.open('GET', 'functions/myTruckInfo.php?user=' + user);
        request.send();
    }

    window.onload = function () {
        getTruckInfo(2);
    }
</script>
<?php include "footer.php"; ?>
