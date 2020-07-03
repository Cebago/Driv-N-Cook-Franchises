function showDetails(idEvent){
    let items = document.getElementsByClassName("cardDetails");
    items = [].slice.call(items);
    items.forEach(function(item){
        console.log(item.id);
        console.log(idEvent);
        if(item.id === idEvent){
            item.style.display = "block";
        }else {
            item.style.display = "none";
        }
    })




}