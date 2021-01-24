var form_inserisciProdotto = {
    "productName": ["Nome prodotto", /^\w+(\s\w+)*$/, "Il nome deve contenere caratteri alfanumerici."],
    "productPrice": ["Prezzo", /^\d+(.\d{1,2})?$/, "Prezzo non corretto."]
};

function setDefaultValue(input) {
    
    if(input.value == "") {
        input.className = "default-text";
        input.value = form_inserisciProdotto[input.id][0];
    }
};

function noDefaultValue(input) {

    if(input.value == form_inserisciProdotto[input.id][0]) {
        input.className = "";
        input.value = "";
    }
};

function load() {

    for(var key in form_inserisciProdotto) {
        var input = document.getElementById(key);
        setDefaultValue(input);
        input.onfocus = function() {
            noDefaultValue(this);
        };
        input.onblur = function() {
            setDefaultValue(this);
        };
    }
};

function printError(input) {
    
    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "formErrors";
    element.appendChild(document.createTextNode(form_inserisciProdotto[input.id][2]));
    parent.appendChild(element);
};

function validateField(input) {

    var parent = input.parentNode;
    if(parent.children.length == 2) {
        parent.removeChild(parent.children[1]);
    }
    var regex = form_inserisciProdotto[input.id][1];
    if(input.value.search(regex) != 0) {
        printError(input);
        return false;
    } else {
        return true;
    }
};

function validateForm() {

    var correct = true;
    for(var key in form_inserisciProdotto) {
        var input = document.getElementById(key);
        var result = validateField(input);
        correct = correct && result;
    }
    return correct;
};