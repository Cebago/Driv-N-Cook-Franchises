function displayOrders() {
    const containerRow = document.getElementById("ordersList");
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            if (request.responseText !== "") {
                let json = JSON.parse(request.responseText);
                for (let i = 0; i < json.length; i++) {
                    // vérifier si la div existe -> if(true) -> mettre à jour la date
                    if (document.getElementById(json[i]["idOrder"]) !== null) {
                        document.getElementById("hour" + json[i]["idOrder"]).innerText = json[i]["time"];
                        let status1 = json[i]["status"][0][0]["statusName"];
                        let status2 = json[i]["status"][0][1]["statusName"];
                        if (document.getElementById("status" + json[i]["idOrder"]).innerText !== status1 + " - " + status2) {
                            document.getElementById("status" + json[i]["idOrder"]).innerText = status1 + " - " + status2;
                        }
                        if (status1 === "Payée" || status2 === "Payée") {
                            if (document.getElementById("payBtn" + json[i]["idOrder"]) !== null) {
                                document.getElementById("payBtn" + json[i]["idOrder"]).remove();
                            }
                        }
                        if ((status1 === "Préparée" || status2 === "Préparée") && (status1 === "Payée" || status2 === "Payée") ) {
                            if (document.getElementById("btngroup" + json[i]["idOrder"]) !== null) {
                                let btngroup = document.getElementById("btngroup" + json[i]["idOrder"]);
                                if (btngroup.firstElementChild.id !== "giveToCustomerBtn") {
                                    while (btngroup.firstChild) {
                                        btngroup.removeChild(btngroup.firstChild);
                                    }
                                    let giveToCustomer = document.createElement("button");
                                    giveToCustomer.id = "giveToCustomerBtn";
                                    giveToCustomer.className = "btn btn-outline-success";
                                    giveToCustomer.innerText = "Livrée au client";
                                    giveToCustomer.setAttribute("onclick", "changeStatus(" + json[i]["idOrder"] +", 4)");
                                    btngroup.appendChild(giveToCustomer);
                                }
                            }
                        }
                        if ((status1 === "Payée" || status2 === "Payée") && (status1 === "Récupérée" || status2 === "Récupérée")) {
                            if (document.getElementById(json[i]["idOrder"]) !== null) {
                                document.getElementById(json[i]["idOrder"]).remove();
                            }
                            continue;
                        }
                        continue;
                    }
                    const div1 = document.createElement("div");
                    div1.className = "col-md-5";
                    div1.id = json[i]["idOrder"];
                    const div2 = document.createElement("div");
                    div2.className = "card mb-5 shadow-sm bg-light";
                    const div3 = document.createElement("div");
                    div3.className = "card-title ml-3";
                    const title = document.createElement("h3");
                    title.className = "mt-3";
                    title.innerText = "Commande n°" + json[i]["idOrder"];
                    div3.appendChild(title);
                    const smallTitle = document.createElement("h6");
                    smallTitle.id = "status" + json[i]["idOrder"];
                    let status1 = json[i]["status"][0][0]["statusName"];
                    let status2 = json[i]["status"][0][1]["statusName"];
                    if (status1 !== "Récupérée" && status2 !== "Récupérée") {
                        const pill1 = document.createElement("span");
                        pill1.className = "badge badge-pill badge-info mr-2";
                        pill1.title = json[i]["status"][0][0]["statusDescription"];
                        pill1.innerText = status1;
                        smallTitle.appendChild(pill1);
                        const pill2 = document.createElement("span");
                        pill2.className = "badge badge-pill badge-success";
                        pill2.title = json[i]["status"][0][1]["statusDescription"];
                        pill2.innerText = status2;
                        smallTitle.appendChild(pill2);
                        //smallTitle.innerText = status1 + " - " + status2;
                        div3.appendChild(smallTitle);
                        if (status1 === "En attente de paiement" || status2 === "En attente de paiement") {
                            const payBtn = document.createElement("button");
                            payBtn.className = "btn btn-primary";
                            payBtn.innerText = "Paiement effectué";
                            payBtn.id = "payBtn" + json[i]["idOrder"];
                            payBtn.setAttribute("onclick", "changeStatus(" + json[i]["idOrder"] +", 1)");
                            div3.appendChild(payBtn);
                        }
                    } else {
                        continue;
                    }
                    div2.appendChild(div3);
                    const div4 = document.createElement("div");
                    div4.className = "card-body";
                    if (json[i]["products"].length !== 0) {
                        const p1 = document.createElement("p");
                        p1.className = "card-text";
                        p1.innerText = "Produits:";
                        div4.appendChild(p1);
                        const ul1 = document.createElement("ul");
                        for (let j = 0; j < json[i]["products"].length; j++) {
                            const li1 = document.createElement("li");
                            li1.innerText = json[i]["products"][j]["productName"];
                            li1.setAttribute("onclick", "strike(this)");
                            ul1.appendChild(li1);
                        }
                        div4.appendChild(ul1);
                    }
                    if (json[i]["menus"].length !== 0) {
                        const p2 = document.createElement("p");
                        p2.className = "card-text";
                        p2.innerText = "Menus:";
                        div4.appendChild(p2);
                        let key = Object.keys(json[i]["menus"]);
                        const ul2 = document.createElement("ul");
                        for (let j = 0; j < key.length; j++) {
                            const li2 = document.createElement("li");
                            li2.innerText = key[j] + ":";
                            const ul3 = document.createElement("ul");
                            for (let k = 0; k < json[i]["menus"][key[j]].length; k++) {
                                const li3 = document.createElement("li");
                                li3.innerText = json[i]["menus"][key[j]][k];
                                li3.setAttribute("onclick", "strike(this)");
                                ul3.appendChild(li3);
                            }
                            li2.appendChild(ul3);
                            ul2.appendChild(li2);
                        }
                        div4.appendChild(ul2);
                    }
                    const div5 = document.createElement("div");
                    div5.className = "d-flex justify-content-between align-items-center";
                    const div6 = document.createElement("div");
                    div6.id = "btngroup" + json[i]["idOrder"]
                    div6.className = "btn-group";
                    const btn1 = document.createElement("button");
                    btn1.type = "button";
                    btn1.className = "btn btn-sm btn-outline-primary";
                    btn1.setAttribute("onclick", "changeStatus(" + json[i]["idOrder"] +", 25)")
                    btn1.innerText = "Traitement";
                    div6.appendChild(btn1);
                    const btn2 = document.createElement("button");
                    btn2.type = "button";
                    btn2.className = "btn btn-sm btn-outline-success";
                    btn2.setAttribute("onclick", "changeStatus(" + json[i]["idOrder"] +", 26)")
                    btn2.innerText = "Terminer";
                    div6.appendChild(btn2);
                    div5.appendChild(div6);
                    const small = document.createElement("small");
                    small.className = "text-muted";
                    small.id = "hour" + json[i]["idOrder"];
                    small.innerText = json[i]["time"];
                    div5.appendChild(small);
                    div4.appendChild(div5);
                    div3.appendChild(div4);
                    div2.appendChild(div3);
                    div1.appendChild(div2);
                    containerRow.appendChild(div1);
                }
            } else {
                containerRow.innerText = "Vous n'avez aucune commande en cours";
            }
        }
    };
    request.open('GET', 'functions/getOrders.php');
    request.send();
}

function strike(thisParameter) {
    if (thisParameter.firstChild.tagName === "STRIKE") {
        let text = thisParameter.firstChild.innerText;
        thisParameter.removeChild(thisParameter.firstChild);
        thisParameter.innerText = text;
    } else {
        let text = thisParameter.innerText;
        thisParameter.innerHTML = "<strike>" + text + "</strike>";
    }
}

function changeStatus(order, status) {
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            if (request.responseText !== "") {
                alert(request.responseText);
            }
        }
    }
    request.open("GET", "functions/changeStatus.php?order=" + order + "&status=" + status);
    request.send();

    displayOrders();
}

function isOnHolidays(truck) {
    const btndiv = document.getElementById("holidayButton");
    while (btndiv.firstChild) {
        btndiv.removeChild(btndiv.firstChild);
    }

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            if (request.responseText !== "") {
                let json = JSON.parse(request.responseText);
                console.log(json);
                for (let i = 0; i < json.length; i++) {
                    const btn = document.createElement("btn");
                    btn.className = "btn btn-primary";
                    if (json[i]["status"] === "14") {
                        btn.innerText = "Fermer le camion pour les vacances";
                        btn.setAttribute("onclick", "changeTruckStatus(" + truck +", 12, 14)");
                    } else if (json[i]["status"] === "12") {
                        btn.innerText = "Ouvert de nouveau"
                        btn.setAttribute("onclick", "changeTruckStatus(" + truck +", 14, 12)");
                    }
                    btndiv.appendChild(btn);
                }
            }
        }
    }
    request.open("GET", "functions/isHolidays.php?truck=" + truck);
    request.send();
}

function changeTruckStatus(truck, status, old) {
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            if (request.responseText !== "") {
                alert(request.responseText)
            }
        }
    }
    request.open("GET", "functions/changeTruckStatus.php?truck=" + truck + "&status=" + status + "&oldStatus=" + old);
    request.send();
    isOnHolidays(truck);
}