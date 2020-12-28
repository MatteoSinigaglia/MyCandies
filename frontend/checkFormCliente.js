var form_login = {
    "loginEmail": ["Inserisci e-mail", /^([a-z0-9]+[_\.-]?)+@([\da-z\.-]+)\.([a-z\.]{2,3})$/, "La mail inserita non è corretta"],
    "loginPassword": ["password", /\w{5,20}/,"La password inserita non è corretta"]
};

var form_registrazione = {
    "email": ["Inserisci e-mail", /^([a-z0-9]+[_\.-]?)+@([\da-z\.-]+)\.([a-z\.]{2,3})$/ , "La mail inserita non è corretta"],
    "password": ["password", /\w{5,20}/,"La password inserita non è corretta"],
    "confirmPassword": ["password", /\w{5,20}/,"La password non corrisponde"],
    "name": ["Inserisci nome", /^\[a-zA-Z]{5,20}/ , "Nome non corretto"],
    "surname": ["Inserisci cognome", /^\[a-zA-Z]{5,20}/, "Cognome non corretto"],
    "address": ["Inserisci indirizzo", /[a-zA-Z]{3}\s[a-zA-Z]+\s\d+/, "Indirizzo non corretto"],
    "area": ["Inserisci provincia", /[A-Z]{2}/, "Provincia non corretta"],
    "city": ["Inserisci città", /[a-zA-Z]{2,20}/, "Città non corretta"],
    "cap": ["Inserisci CAP", /\d{5}/, "CAP non corretto"]
};

function defaultLoginValue(input) {
    if(input.value == "") {
        input.className = "default-text";
        input.value = form_login[input.id][0];
    }
};

function defaultRegistrationValue(input) {
    if(input.value == "") {
        input.className = "default-text";
        input.value = form_registrazione[input.id][0];
    }
};

function noDefaultLogin(input) {
    if (input.value == form_login[input.id][0]) {
        input.value = "";
        input.className = "";
    }
};

function noDefaultRegistration(input) {
    if (input.value == form_registrazione[input.id][0]) {
        input.value = "";
        input.className = "";
    }
};

function load() {
    for(var i in form_login) {
        var input = document.getElementById(i);
        defaultLoginValue(input);
        input.onfocus = function() {
            noDefaultLogin(this);
        };
        input.onblur = function() {
            defaultLoginValue(this);
        };
    }

    for(var i in form_registrazione) {
        var input = document.getElementById(i);
        defaultRegistrationValue(input);
        input.onfocus = function() {noDefaultRegistration(this);};
        input.onblur = function() {defaultRegistrationValue(this);};
    }
};

/* Controllo campi per il login
 *
**/
function printLoginError(input) {

    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "formErrors";
    element.appendChild(document.createTextNode(form_login[input.id][2]));
    parent.appendChild(element);
};

function validateLoginField(input) {
    var parent = input.parentNode;
    if(parent.children.length == 2) {
        parent.removeChild(parent.children[1]);
    }

    var regex = form_login[input.id][1];
    if(input.value.search(regex) != 0) {
        printLoginError(input);
        return false;
    } else {
        return true;
    }
};

function validateLoginForm() {
    var correct = true;
    for(var key in form_login) {
        var input = document.getElementById(key);
        var result = validateLoginField(input);
        correct = correct && result;
    }
    return correct;
};


/* Controllo campi per la registrazione
 *
 **/

function printRegError(input) {

    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "formErrors";
    element.appendChild(document.createTextNode(form_registrazione[input.id][2]));
    parent.appendChild(element);
};

function printRightPassword(input) {

    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "rightMessage";
    element.appendChild(document.createTextNode("Le password corrispondono"));
    parent.appendChild(element);
};

function validateRegField(input) {

    var parent = input.parentNode;
    if(parent.children.length == 2) {
        parent.removeChild(parent.children[1]);
    }

    var regex = form_registrazione[input.id][1];
    if(input.value.search(regex) != 0) {
        printRegError(input);
        return false;
    } else {
        if(input.id == 'confirmPassword') {
            var psw = document.getElementById('password');
            if(input.value != psw.value) {
                printRegError(input);
                return false;
            } else {
                printRightPassword(input);
                return true;
            }
        }
    }
};

function validateRegForm() {
    var correct = true;
    for(var key in form_registrazione) {
        var input = document.getElementById(key);
        var result = validateRegField(input);
        correct = correct && result;
    }
    return correct;
};
