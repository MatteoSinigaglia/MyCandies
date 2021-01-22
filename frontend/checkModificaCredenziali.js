var form_credenziali = {
    "changePassword": ["Inserisci nuova password", /.{4,20}/, "Password non valida. La lunghezza deve essere tra 5 e 20 caratteri."],
    "confirmNewPassword": ["Inserisci password di conferma", /.{4,20}/, "La password non corrisponde."]
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
        if(input.id == "confirmNewPassword") {
            var confronto = document.getElementById("changePassword");
            if(input.value != confronto.value) {
                printError(input);
                return false;
            }
        }
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