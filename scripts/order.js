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
                        continue;
                    }
                    const div1 = document.createElement("div");
                    div1.className = "col-md-5";
                    div1.id = json[i]["idOrder"];
                    const div2 = document.createElement("div");
                    div2.className = "card mb-5 shadow-sm";
                    const div3 = document.createElement("div");
                    div3.className = "card-title";
                    const title = document.createElement("h3");
                    title.innerText = "Commande n°" + json[i]["idOrder"];
                    div3.appendChild(title);
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
                    div6.className = "btn-group";
                    const btn1 = document.createElement("button");
                    btn1.type = "button";
                    btn1.className = "btn btn-sm btn-outline-primary";
                    btn1.innerText = "Traitement";
                    div6.appendChild(btn1);
                    const btn2 = document.createElement("button");
                    btn2.type = "button";
                    btn2.className = "btn btn-sm btn-outline-success";
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
        if (request.readyState === 4 && request.status === 400) {
            if (request.responseText !== "") {
                alert(request.responseText);
            } else {
                displayOrders();
            }
        }
    }
    request.open("GET", "../functions/getOrders.php?order=" + order + "&status=" + status);
    request.send();
}