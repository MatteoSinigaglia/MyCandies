var form_credenziali = {
    "changeEmail": ["Inserisci nuova e-mail", /^([a-z0-9]+[_\.-]?)+@([\da-z\.-]+)\.([a-z\.]{2,6})$/, "E-mail non valida"],
    "changePassword": ["Inserisci password", /.{5,20}/, "Password non valida"]
};

function defaultValue(input) {
    if(input.value == "") {
        input.className = "default-text";
        input.value = form_credenziali[input.id][0];
    }
};

function noDefaultValue(input) {
    if(input.value == form_credenziali[input.id][0]) {
        input.className = "";
        input.value = "";
    }
};

function load() {
    for(var key in form_credenziali) {
        var input = document.getElementById(key);
        defaultValue(input);
        input.onfocus = function() {noDefaultValue(this);};
        input.onblur = function() {defaultValue(this);};
    }
};

function printError(input) {
    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "formErrors";
    element.appendChild(document.createTextNode(form_credenziali[input.id][2]));
    parent.appendChild(element);
};

function validateField(input) {
    var parent = input.parentNode;
    if(parent.children.length == 2) {
        parent.removeChild(parent.children[1]);
    }

    var regex = form_credenziali[input.id][1];
    if(input.value.search(regex) != 0) {
        printError(input);
        return false;
    } else {
        return true;
    }
};

function validateForm() {
    var correct = true;
    for(var key in form_credenziali) {
        var input = document.getElementById(key);
        var result = validateField(input);
        correct = correct && result;
    }
    return correct;
};