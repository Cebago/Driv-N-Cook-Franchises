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
        <div class="w-75">
            <div class="col-md-6 mx-auto" id="schedule"></div>
            <div class="mx-auto col-md-6">
                <button class="btn btn-dark" type="button" data-toggle="modal" title="Modifier mes horaires d'ouvertures"
                        data-target="#hourModal" onclick="getOpenDays(2); setTimeout(changeStatus, 500)">
                    Modifier mes horaires
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hourModal" tabindex="-1" role="dialog" aria-labelledby="hourModal" aria-hidden="true">
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
                                <input type="checkbox" class="custom-control-input" onclick="changeStatus()" name="Check" id="<?php echo $i ?>">
                                <label class="custom-control-label" for="<?php echo $i ?>">Fermé</label>
                            </div>
                            <div class="input-group flex-nowrap mt-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Ouverture</span>
                                </div>
                                <input type="time" class="form-control openHour" name="<?php echo $day[$i] ?>" placeholder="Horaire d'ouverture" aria-describedby="addon-wrapping">
                            </div>
                            <div class="input-group flex-nowrap mt-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Fermeture</span>
                                </div>
                                <input type="time" class="form-control closeHour" name="<?php echo $day[$i] ?>" placeholder="Horaire de fermerture" aria-describedby="addon-wrapping">
                            </div>
                        </div>
                        <a onclick="addHour('<?php echo $day[$i] ?>')" href="#"><i class="fas fa-plus"></i>
                            &nbsp;Ajouter un nouvel horaire
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button class="btn btn-success" data-dismiss="modal" type="button" onclick="">Modifier les horaires</button>
            </div>
        </div>
    </div>
</div>

<script>

    function getOpenDays(user) {
        const request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                let myJson = JSON.parse(request.responseText);
                myJson = myJson["opendays"];
                for (let i = 0; i < myJson.length; i++) {
                    const div = document.getElementsByClassName("modal-title");
                    for (let j = 0; j < div.length; j++) {
                        if (div[j].textContent === myJson[i]["openDay"]) {
                            let child = div[j].parentElement.childNodes;
                            for (let count1 = 0; count1 < child.length; count1++) {
                                if (child[count1].tagName === "DIV") {
                                    let child2 = child[count1].childNodes;
                                    for (let count2 = 0; count2 < child2.length; count2++) {
                                        if (child2[count2].tagName === "DIV") {
                                            let child3 = child2[count2].childNodes;
                                            for (let count3 = 0; count3 < child3.length; count3++) {
                                                if (child3[count3].tagName === "INPUT" && child3[count3].className === "custom-control-input") {
                                                    let check = child3[count3];
                                                    check.checked = true;
                                                }
                                                if (child3[count3].tagName === "INPUT" && child3[count3].className === "form-control openHour") {
                                                    let input = child3[count3];
                                                    input.value = myJson[i]["startHour"];
                                                } else if (child3[count3].tagName === "INPUT" && child3[count3].className === "form-control closeHour") {
                                                    let input = child3[count3];
                                                    input.value = myJson[i]["endHour"];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        request.open('GET','functions/myTruckInfo.php?user=' + user);
        request.send();
        changeStatus();
    }

    function changeStatus() {
        const check = document.getElementsByName("Check");
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
        request.open('GET','functions/myTruckInfo.php?user=' + user);
        request.send();
    }

    function addHour(thisDay) {
        const parent = document.getElementById("parent"+thisDay);
        const pDiv = document.createElement("div");
        const open = document.createElement("div");
        open.className = "input-group flex-nowrap mt-1";
        const openDiv1 = document.createElement("div");
        openDiv1.className = "input-group-prepend";
        const openSpan = document.createElement("span");
        openSpan.className = "input-group-text";
        openSpan.innerText = "Ouverture";
        openDiv1.appendChild(openSpan);
        const openInput = document.createElement("input");
        openInput.name = thisDay;
        openInput.type = "time";
        openInput.className = "form-control openHour";
        openInput.placeholder = "Horaire d'ouverture";
        open.appendChild(openDiv1);
        open.appendChild(openInput);

        const close = document.createElement("div");
        close.className = "input-group flex-nowrap mt-1";
        const closeDiv1 = document.createElement("div");
        closeDiv1.className = "input-group-prepend";
        const closeSpan = document.createElement("span");
        closeSpan.className = "input-group-text";
        closeSpan.innerText = "Fermeture";
        closeDiv1.appendChild(closeSpan);
        const closeInput = document.createElement("input");
        closeInput.name = thisDay;
        closeInput.type = "time";
        closeInput.className = "form-control closeHour";
        closeInput.placeholder = "Horaire de fermeture";
        close.appendChild(closeDiv1);
        close.appendChild(closeInput);
        parent.appendChild(close);

        pDiv.appendChild(open);
        pDiv.appendChild(close);

        parent.appendChild(pDiv);

    }

    window.onload = function() {
        getTruckInfo(2);
    }
</script>
<?php include "footer.php"; ?>
