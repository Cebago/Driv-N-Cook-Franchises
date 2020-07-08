function getLocation(truck) {
    idTruck = truck; //rend la variable globale pour qu'elle soit accessible dans le reste du code

    if (navigator.geolocation) {//si la geoloc est activé sur le navigateur
        //on appelle la méthode getCurrentPosition qui appelle l'api du navigateur (par défaut google)
        //on lui met en paramètre les fonctions qui seront appellées en retour, success or error
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    } else {
        errorCallback()
    }


}

function errorCallback(result) {
    $("#toastKO").toast('show');
    console.log(result);
}

function successCallback(position) {
    saveMyLoc(position.coords.longitude, position.coords.latitude);
}

function saveMyLoc(lng, lat) {
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            $("#toastOK").toast('show');
        }
    }
    request.open("GET", "functions/saveMyLoc.php?truck=" + idTruck + "&lng=" + lng + "&lat=" + lat);
    request.send();
}