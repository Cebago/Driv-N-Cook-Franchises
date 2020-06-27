function deleteFirst(thisParameter) {
    if (thisParameter[0].innerText === "Choisir ... ") {
        thisParameter[0].remove();
    }
}