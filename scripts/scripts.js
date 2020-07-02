function readMessage(idContact){
    let row = document.getElementById('row'+idContact);
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                row.setAttribute("style", "fontWeight = 'normal'");
            }
        }
    };
    request.open('GET', 'functions/readMessage.php?idContact='+ idContact);
    request.send();

}