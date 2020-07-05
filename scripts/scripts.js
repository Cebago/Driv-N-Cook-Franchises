function showDetails(idEvent){
    let items = document.getElementsByClassName("cardDetails");
    items = [].slice.call(items);
    items.forEach(function(item){

        if(item.id === idEvent){
            item.style.display = "block";
        }else {
            item.style.display = "none";
        }
    })
}


function chooseImage() {
    const container = document.getElementById("useOne");
    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            if (request.responseText !== "") {
                let jsonImages = JSON.parse(request.responseText);
                for (let i = 0; i < jsonImages.length; i++) {
                    const a = document.createElement("a");
                    jsonImages[i] = jsonImages[i].substr(3);

                    a.setAttribute("onclick", "selectImg(this,'"+jsonImages[i]+"')");
                    const img = document.createElement("img");
                    img.src = jsonImages[i];
                    img.className = "ml-2 mr-2 mt-2 mb-2 images";
                    img.width = 200;
                    img.height = 133;
                    img.alt = jsonImages[i];
                    a.appendChild(img);
                    container.appendChild(a);
                }
            }
        }
    }
    request.open("GET", "./functions/getImages.php", true);
    request.send();
}

function selectImg(thisParameter, imgUrl) {
    images = document.getElementsByClassName("images");
    images = [].slice.call(images);
    images.forEach(function(item){
        item.style.border = "";
    })
    thisParameter.firstChild.style.border = "5px solid #96CC87";
    const image = document.getElementById("inputImg");
    image.value = imgUrl
}
