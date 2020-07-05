
function getIngredientTruck() {
    const table = document.getElementById("ingredients");
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            //console.log(request.responseText);
            table.innerHTML = request.responseText;
        }
    };

    request.open('GET', 'functions/getIngredientTruck.php');
    request.send();
}

function disableIngredient(ingredient) {
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            if (request.responseText !== "") {
                alert(request.responseText);

            }
        }
    };

    request.open('POST', 'functions/disableIngredient.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('ingredient=' + ingredient);
    getIngredientTruck();
}

function addIngredient() {
    let checkbox = document.getElementsByName("checkbox");
    const deleteMe = document.getElementById("deleteMe");
    const selectDiv = document.getElementById("selectDiv");
    console.log(checkbox[0].checked);
    if (!checkbox[0].checked) {

        const child = document.createElement('div');

        const inputName = document.createElement('input');
        inputName.type = "text";
        inputName.id = "disabledTextInput";
        inputName.name = "newIngredient";
        inputName.setAttribute("required", "required");
        inputName.className = "form-control mt-3";
        inputName.placeholder = "Nom de l'ingrédient";
        child.appendChild(inputName);

        const inputNameEN = document.createElement('input');
        inputNameEN.type = "text";
        inputNameEN.id = "disabledTextinputNameEN";
        inputNameEN.name = "newIngredientEN";
        inputNameEN.className = "form-control mt-3 ";
        inputNameEN.placeholder = "Traduction anglaise";
        child.appendChild(inputNameEN);

        const inputNameES = document.createElement('input');
        inputNameES.type = "text";
        inputNameES.id = "disabledTextInputNameES";
        inputNameES.name = "newIngredientES";
        inputNameES.className = "form-control mt-3";
        inputNameES.placeholder = "Traduction espagnole";
        child.appendChild(inputNameES);

        const div1 = document.createElement('div');
        div1.className = "custom-file mt-3";
        const input2 = document.createElement('input');
        input2.type = "file";
        input2.className = "custom-file-input";
        input2.id = "validatedCustomFile";
        input2.name = "ingredientImg";
        input2.setAttribute("required", "required");
        div1.appendChild(input2);

        const label2 = document.createElement('label');
        label2.className = "custom-file-label";
        label2.setAttribute("for", "validatedCustomFile");
        label2.innerText = "Choisir une image...";
        div1.appendChild(label2);
        child.appendChild(div1);

        deleteMe.appendChild(child);

        while (selectDiv.firstChild) {
            selectDiv.removeChild(selectDiv.firstChild);
        }
    } else {
        while (deleteMe.firstChild) {
            deleteMe.removeChild(deleteMe.firstChild);
        }
        if (!selectDiv.firstChild) {
            const label1 = document.createElement('label');
            label1.setAttribute("for", "selectIngredientName");
            label1.id = "selectName";
            label1.innerText = "Nom";
            selectDiv.appendChild(label1);
            const select1 = document.createElement("select");
            select1.className = "form-control";
            select1.id = "selectIngredientName";
            selectDiv.appendChild(select1);
            showCategory();
        }
    }
}

function addInBdd() {
    const ingredient = document.getElementById('selectIngredientName');
    const category = document.getElementById('selectCategory');
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            if (request.responseText !== "") {
                alert(request.responseText);

            }
        }
    };
    request.open('POST', 'functions/addInBdd.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('ingredient=' + ingredient + '&category=' + category);
    getIngredientTruck();
}

function showCategory() {
    const select = document.getElementById("selectCategory");
    const name = document.getElementById("selectName");
    if (select.value !== "Choisir une catégorie.." && name !== null) {
        name.innerText = select.value;
        if (select[0].value === "Choisir une catégorie..") {
            select.removeChild(select[0]);
        }

    }
    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            if (request.responseText !== "") {
                let myjson = JSON.parse(request.responseText);
                const selectName = document.getElementById("selectIngredientName");
                while (selectName.firstChild) {
                    selectName.removeChild(selectName.firstChild);
                }
                for (let i = 0; i < myjson.length; i++) {
                    const option = document.createElement("option");
                    option.value = myjson[i]["ingredientName"];
                    option.innerText = myjson[i]["ingredientName"];
                    selectName.appendChild(option);
                }

            }
        }
    };
    request.open('POST', 'functions/selectIngredient.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('ingredient=' + select.value);
}
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