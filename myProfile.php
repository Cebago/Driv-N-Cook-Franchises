<?php
session_start();
require "conf.inc.php";
require "functions.php";

if (isConnected() && isActivated() && isFranchisee()) {
include 'header.php';
?>
<body>
<?php include "navbar.php"; ?>
<div class="col-md-11 mx-auto mt-3">
    <h5 class="" id="seeProfile">Consulation du profil</h5>
</div>
<form class="col-md-11 mx-auto mt-3" action="./functions/updateProfile.php" method="POST">
    <div class="mt-5" id="profile"></div>
    <div class="col-md-11 mt-5 mx-auto" id="buttons">
        <button class="btn btn-primary ml-3 float-left" type="button" onclick="unlockForm()"><i
                    class="fas fa-unlock-alt"></i>&nbsp;Débloquer le formulaire
        </button>
    </div>
</form>
<script>
    function getProfileDetails() {
        const profile = document.getElementById('profile');

        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                //console.log(request.responseText);
                let myJson = JSON.parse(request.responseText);
                let myJsonKeys = Object.keys(myJson[0])
                for (let i = 0; i < myJsonKeys.length; i++) {
                    let pdiv = document.createElement("div");
                    let cdiv = document.createElement("div");
                    let span = document.createElement("span");
                    let input = document.createElement("input");

                    pdiv.className = "input-group flex-nowrap mt-2";
                    cdiv.className = "input-group-prepend";
                    span.id = myJsonKeys[i];
                    span.className = "input-group-text";

                    if (myJsonKeys[i] === "idUser") {
                        span.innerHTML = "Numéro d'utilisateur";
                    } else if (myJsonKeys[i] === "emailAddress") {
                        span.innerHTML = "Adresse email";
                    } else if (myJsonKeys[i] === "lastname") {
                        span.innerHTML = "Nom de famille";
                    } else if (myJsonKeys[i] === "firstname") {
                        span.innerHTML = "Prénom";
                    } else if (myJsonKeys[i] === "createDate") {
                        span.innerHTML = "Date de création du compte";
                    } else if (myJsonKeys[i] === "licenseNumber") {
                        span.innerHTML = "Numéro de permis";
                    } else if (myJsonKeys[i] === "postalCode") {
                        span.innerHTML = "Code postal";
                    } else if (myJsonKeys[i] === "phoneNumber") {
                        span.innerHTML = "Numéro de téléphone";
                    } else if (myJsonKeys[i] === "roleName") {
                        span.innerHTML = "Votre rôle sur le site";
                    } else if (myJsonKeys[i] === "address") {
                        span.innerHTML = "Adresse";
                    } else if (myJsonKeys[i] === "fidelityCard") {
                        span.innerHTML = "Votre numéro de carte fidélité";
                    } else if (myJsonKeys[i] === "city") {
                        span.innerHTML = "Ville";
                    }

                    cdiv.appendChild(span);
                    pdiv.appendChild(cdiv);
                    input.id = myJsonKeys[i];
                    input.name = myJsonKeys[i];
                    input.className = "form-control";
                    input.placeholder = myJsonKeys[i];
                    input.value = myJson[0][myJsonKeys[i]];
                    input.setAttribute("readonly", "readonly");
                    pdiv.appendChild(input);
                    profile.appendChild(pdiv);
                }
            }
        };
        request.open('GET', 'functions/profilDetails.php');
        request.send();
    }

    window.onload = getProfileDetails;

    function unlockForm() {
        const input = document.getElementsByClassName('form-control');
        for (let i = 0; i < input.length; i++) {
            if (input[i].id !== "idUser"
                && input[i].id !== "fidelityCard"
                && input[i].id !== "roleName"
                && input[i].id !== "createDate"
                && input[i].id !== "emailAddress") {
                input[i].removeAttribute('readonly');
            }
        }
        createFormButton();
    }

    function createFormButton() {
        const valid = document.getElementById('valid');
        const cancel = document.getElementById('cancel');
        if (valid === null && cancel === null) {
            const buttonDiv = document.getElementById('buttons');
            const buttonValidation = document.createElement('button');
            const cancelButton = document.createElement('a');
            buttonValidation.innerHTML = '<i class="fas fa-check"></i>&nbsp;Valider le formulaire';
            buttonValidation.className = "btn btn-success float-right mr-3 btn-lg";
            buttonValidation.type = "submit";
            buttonValidation.id = "valid";

            cancelButton.innerHTML = '<i class="fas fa-times"></i>&nbsp;Annuler le formulaire';
            cancelButton.className = "btn btn-danger float-right mr-3 btn-lg";
            cancelButton.href = "./myProfile.php"
            cancelButton.type = "button";
            cancelButton.id = "cancel";

            buttonDiv.appendChild(buttonValidation);
            buttonDiv.appendChild(cancelButton);
        }
    }

</script>
<?php
include "footer.php";
} else {
    header("Location: login.php");
}
?>
