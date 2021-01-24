function printError(input, num, dati) {
    removeError(input);
    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "formErrors";
    switch(dati) {
        case "form_login": element.appendChild(document.createTextNode(form_login[input.id][num]));
        break;
        case "form_registrazione": element.appendChild(document.createTextNode(form_registrazione[input.id][num]));
        break;
        case "form_inserisciProdotto": element.appendChild(document.createTextNode(form_inserisciProdotto[input.id][num]));
        break;
        case "form_credenziali": element.appendChild(document.createTextNode(form_credenziali[input.id][num]));
        break;
        case "PAFormDetails": element.appendChild(document.createTextNode(PAFormDetails[input.id][num]));
        break;
    }
    parent.appendChild(element);
}

function removeError(input) {
    var parent = input.parentNode;
    if(parent.children.length == 2) {
        parent.removeChild(parent.children[1]);
    }
};

function printRightPassword(input) {
    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "rightMessage";
    element.appendChild(document.createTextNode("Le password corrispondono"));
    parent.appendChild(element);
};

function defaultValue(input, dati) {
    if (input.value == "") {
        input.className = "default-text";
        switch(dati) {
            case "form_login": input.value = form_login[input.id][0];
            break;
            case "form_registrazione": input.value = form_registrazione[input.id][0];
            break;
            case "form_inserisciProdotto": input.value = form_inserisciProdotto[input.id][0];
            break;
            case "form_credenziali": input.value = form_credenziali[input.id][0];
            break;
        }
    }
};

function noDefaultValue(input, dati) {
    switch(dati) {
        case "form_login": {
            if (input.value == form_login[input.id][0]) {
                input.value = "";
                input.className = "";
            }
        }
        break;
        case "form_registrazione": {
            if (input.value == form_registrazione[input.id][0]) {
                input.value = "";
                input.className = "";
            }
        }
        break;
        case "form_inserisciProdotto": {
            if(input.value == form_inserisciProdotto[input.id][0]) {
                input.className = "";
                input.value = "";
            }
        }
        break;
        case "form_credenziali": {
            if(input.value == form_credenziali[input.id][0]) {
                input.className = "";
                input.value = "";
            }
        }
        break;
    }
};

var form_login = {
    "loginEmail": ["Inserisci e-mail", /^([a-z0-9]+[_\.-]?)+@([\da-z\.-]+)\.([a-z\.]{2,6})$/, "La mail inserita non è corretta."],
};

var form_registrazione = {
    "email": ["Inserisci e-mail", /^([a-z0-9]+[_\.-]?)+@([\da-z\.-]+)\.([a-z\.]{2,6})$/ , "La mail inserita non è corretta."],
    "password": ["password", /.{4,20}/,"Password non valida. La lunghezza deve essere tra 4 e 20 caratteri."],
    "confirmPassword": ["", /.{4,20}/,"La password non corrisponde a quella scelta."],
    "name": ["Inserisci nome", /^[A-Z][a-z]{2,20}(\s[A-Z][a-z]{2,20})?$/ , "Nome non corretto. Il nome deve iniziare con una maiuscola."],
    "surname": ["Inserisci cognome", /^[A-Z][a-z]{2,20}(\s[A-Z][a-z]{2,20})?$/, "Cognome non corretto. Il nome deve iniziare con una maiuscola."],
    "birthDate": ["Inserisci data (DD-MM-YYYY)", /^\d{2}-\d{2}-\d{4}$/, "Formato data non corretto. Inserire (DD-MM-YYYY).", "Utente minorenne non consentito.", "Data non valida."],
    "address": ["Inserisci via", /^([a-zA-Z]{3}\s)?[a-zA-Z]+(\s[a-zA-Z])*$/, "Indirizzo non corretto."],
    "address_number": ["Inserisci civico", /^[0-9]{1,3}([a-zA-Z]?)$/, "Civico non corretto."],
    "city": ["Inserisci comune", /^([a-zA-Zàèìòù]{2,20}\s?)+$/, "Comune non corretto.", "Compila il campo."],
    "area": ["Inserisci provincia", /^[A-Z]{2}$/, "Provincia non corretta. Inserire i caratteri maiuscoli."],
    "cap": ["Inserisci CAP", /^\d{5}$/, "CAP non corretto. Il CAP è una sequenza numerica di 5 valori."],
    "telefono": ["Inserisci cellulare", /^\d{10}$/, "Cellulare non corretto. Il numero deve iniziare con la cifra '3'."]
};

function loadFormCliente() {
    for(var i in form_login) {
        var input = document.getElementById(i);
        defaultValue(input, "form_login");
        input.onfocus = function() { noDefaultValue(this, "form_login"); };
        input.onblur = function() { defaultValue(this, "form_login"); };
    }

    for(var i in form_registrazione) {
        var input = document.getElementById(i);
        defaultValue(input, "form_registrazione");
        input.onfocus = function() { noDefaultValue(this, "form_registrazione"); };
        input.onblur = function() { defaultValue(this, "form_registrazione"); };
    }
};

/* Controllo campi per il login
**/
function validateLoginField(input) {
    removeError(input);
    var regex = form_login[input.id][1];
    if(input.value.search(regex) != 0) {
        printError(input, 2, "form_login");
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
 **/

function validateDate(input) {
    var comp = input.value.split("-");
    var today = new Date();
    var birthDate = new Date(comp[2], comp[1]-1, comp[0]);
    // controllo validità della data 
    var giorno = parseInt(comp[0]);
    var mese = parseInt(comp[1]);
    var anno = parseInt(comp[2]);
    if (birthDate.getTime() > today.getTime() || anno <= today.getFullYear()-100 || mese == 0 || mese > 12) { 
        return false; 
    }
    var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if (anno%4 == 0) { monthLength[1] = 29; }
    return giorno > 0 && giorno <= monthLength[mese - 1];
};

function otherCheck(input) {
    var value = input.id;
    switch(value) {
        case "birthDate": {  
            if (!validateDate(input)) {
                printError(input, 4, "form_registrazione");
                return false;
            } else {
                // controllo sull'età del utente solo se la data è accettata
                var c = input.value.split("-");
                var t = new Date();
                var birthDate = new Date(c[2], c[1]-1, c[0]);
                var age = t.getFullYear() - birthDate.getFullYear();
                var m = t.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && t.getDate() < birthDate.getDate())) {
                    age--;
                }
                if (age <= 18) {
                    printError(input, 3, "form_registrazione");
                    return false;
                }
                return true;
            }
        } 
        case "confirmPassword": {
            var psw = document.getElementById('password');
            if(input.value != psw.value) {
                printError(input, 2, "form_registrazione");
                return false;
            } else {
                printRightPassword(input);
                return true;
            }
        }
        case "telefono": {
            if(input.value.charAt(0) != 3) {
                printError(input, 2, "form_registrazione");
                return false;
            } else {
                return true;
            }
        }
        case "city": {
            if(input.value == form_registrazione[input.id][0]) {
            printError(input, 3, "form_registrazione");
            return false;
            }
        }
    }
};

function validateRegField(input) {
    removeError(input);
    var regex = form_registrazione[input.id][1];
    if(input.value.search(regex) != 0) {
        printError(input, 2, "form_registrazione");
        return false;
    } else {
        if(input.id == "birthDate" || input.id == "confirmPassword" || input.id == "telefono" || input.id == "city") {
            return otherCheck(input);
        } else {
            return true; 
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

/*
============= CHECK INSERIMENTO PRODOTTO
*/
var form_inserisciProdotto = {
    "productName": ["Nome prodotto", /^\w+(\s\w+)*$/, "Nome non corretto."],
    "productPrice": ["Prezzo", /^\d+(,\d{1,2})?$/, "Prezzo non corretto."]
};
function loadProductInsertion() {
    for(var key in form_inserisciProdotto) {
        var input = document.getElementById(key);
        defaultValue(input, "form_inserisciProdotto");
        input.onfocus = function() { noDefaultValue(this, "form_inserisciProdotto"); };
        input.onblur = function() { defaultValue(this, "form_inserisciProdotto"); };
    }
};

function validateInsertionField(input) {
    removeError(input);
    var regex = form_inserisciProdotto[input.id][1];
    if(input.value.search(regex) != 0) {
        printError(input, 2, "form_inserisciProdotto");
        return false;
    } else {
        if (input.id == "productName" && input.value == form_inserisciProdotto[input.id][0]) {
            printError(input, 3, "form_inserisciProdotto");
            return false;
        }
        return true;
    }
};

function validateInsertionForm() {
    var correct = true;
    for(var key in form_inserisciProdotto) {
        var input = document.getElementById(key);
        var result = validateInsertionField(input);
        correct = correct && result;
    }
    return correct;
};

/*
============= CHECK MODIFICA CREDENZIALI
*/

var form_credenziali = {
    "changePassword": ["Inserisci nuova password", /.{4,20}/, "Password non valida. La lunghezza deve essere tra 4 e 20 caratteri.", "Compilare il campo password."],
    "confirmNewPassword": ["Inserisci password di conferma", /.{4,20}/, "La password non corrisponde."]
};

function loadChangeCredential() {
    for(var key in form_credenziali) {
        var input = document.getElementById(key);
        defaultValue(input, "form_credenziali");
        input.onfocus = function() { noDefaultValue(this, "form_credenziali"); };
        input.onblur = function() { defaultValue(this, "form_credenziali"); };
    }
};

function validateChangeCredentialField(input) {
    removeError(input);
    var regex = form_credenziali[input.id][1];
    if(input.value.search(regex) != 0) {
        printError(input, 2, "form_credenziali");
        return false;
    } else {
        if(input.id == "confirmNewPassword") {
            var confronto = document.getElementById("changePassword");
            if(input.value != confronto.value) {
                printError(input, 2, "form_credenziali");
                return false;
            }
        }
        if(input.id == "changePassword" && input.value == form_credenziali[input.id][0]) {
            printError(input, 3, "form_credenziali");
            return false;
        }
        return true;
    }
};

function validateChangeCredentialForm() {
    var correct = true;
    for(var key in form_credenziali) {
        var input = document.getElementById(key);
        var result = validateChangeCredentialField(input);
        correct = correct && result;
    }
    return correct;
};

/*
============== VALIDATEAREAPERS
*/
var PAFormDetails = {
    "nome": [/^[A-Z][a-z]{2,20}(\s[A-Z][a-z]{2,20})?$/, "Nome non valido."],
    "cognome": [/^[A-Z][a-z]{2,20}(\s[A-Z][a-z]{2,20})?$/, "Cognome non valido."],
    "comune": [/^([a-zA-Zàèìòù]{2,20}\s?)+$/, "Il comune inserito non è corretto."],
    "provincia": [/^[A-Z]{2}$/, "Provincia non corretta. Inserire la sigla in maiuscolo."],
    "indirizzo": [/^([a-zA-Z]{3}\s)?[a-zA-Z]+(\s[a-zA-Z])*$/, "Indirizzo non valido. Inserire l'indirizzo di residenza."],
    "civico": [/^[0-9]{1,3}([a-zA-Z]?)$/, "Numero civico non corretto.Inserire il numero civico della propria residenza."],
    "cap": [/^\d{5}$/, "CAP non valido. Inserire il CAP della propria residenza (5 cifre)."],
    "telefono": [/^\s?([0-9]{10})\s?$/, "Numero non valido. Inserire il proprio numero di cellulare (10 cifre).", "Numero non valido. Il numero deve iniziare con la cifra 3."],
    "data": [/^\d{2}-\d{2}-\d{4}$/, "Data non valida. Inserire una data di nascita rispettando il formato DD/MM/YYYY.", "Data non valida. Utente non maggiorenne.", "Data non valida. Inserire una data possibile."]
};

function PAShowErr(input, num) {
    var parent = input.parentNode; 
    var ele = document.createElement("strong");
    ele.className = "formErrors";
    ele.appendChild(document.createTextNode(PAFormDetails[input.id][num]));
    parent.appendChild(ele);
};

function SpecialTest(input){
switch (input.id) {
    case "telefono": { //deve iniziare con 3
        if (input.value.charAt(0) != 3) {
            PAShowErr(input, 2);
            return false;
        } else {
            return true;
        }
    }

    case "data": {
    var accepted = true;
    var err = 1;
    var comp = input.value.split("-");
    var today = new Date();
    var birthDate = new Date(comp[2], comp[1]-1, comp[0]);
    // controllo validità della data 
    var giorno = parseInt(comp[0]);
    var mese = parseInt(comp[1]);
    var anno = parseInt(comp[2]);
    if (birthDate.getTime() > today.getTime() || anno <= today.getFullYear()-100 || mese == 0 || mese > 12) { 
        err = 3;
        accepted = false; 
    }
    if (accepted) {
        var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        if (anno%4 == 0) { monthLength[1] = 29; }
        accepted = giorno > 0 && giorno <= monthLength[mese - 1];
        if (!accepted) err = 3;
    }

    if (accepted) {
        // DATA VALIDA -> controllo utente maggiorrenne
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (age < 18) {
            err = 2;
            accepted = false;
        }
    }
    // se ho trovato un errore lo stampo
    if(err != 1) { PAShowErr(input, err); }
    return accepted;
    }
}
};

function PAFieldValidate(input) {
    removeError(input);
    var PAregex = PAFormDetails[input.id][0];
    if (input.value.search(PAregex) != 0) {
        PAShowErr(input, 1);
        return false;
    } else {
    if (input.id == "data" || input.id == "telefono" || input.id == "indirizzo") {
        return SpecialTest(input);   
    }
    return true;
}
};

function validatePAForm(){
var PAcheck = true;
for(var key in PAFormDetails){
    var input = document.getElementById(key);
    var res = PAFieldValidate(input);
    PAcheck = PAcheck && res;
}
return PAcheck;
};

var FilterValues = {
    "minActivePrinciple": [/^\d{1,3}$/,"Inserire un numero a max. 3 cifre.", "Valore minimo troppo basso.", "Valore minimo troppo alto.", "Il valore minimo è più grande del valore massimo."],
    "maxActivePrinciple": [/^\d{1,3}$/,"Inserire un numero a max. 3 cifre.", "Valore massimo troppo basso.", "Valore massimo troppo alto."],
    "minPrice": [/^\d{1,4}$/,"Formato prezzo minimo non valido.", "Prezzo minimo troppo basso.", "Il valore minimo è più grande del valore massimo."],
    "maxPrice": [/^\d{1,4}$/,"Formato prezzo massimo non valido.", "Prezzo massimo troppo basso."]
  };
  
  function selectCorrectSpan(input) {
    var span;
    switch(input.id) {
      case "minActivePrinciple": {
        span = document.getElementById("minAP_errors");
      }
        break;
      case "maxActivePrinciple": {
        span = document.getElementById("maxAP_errors");
      }
        break;
      case "minPrice": {
        span = document.getElementById("minPR_errors");
      }
        break;
      case "maxPrice": { 
        span = document.getElementById("maxPR_errors");
      }
        break;
    }
    return span;
  };
  
  function filterShowErr(input, num) {
    var span = selectCorrectSpan(input);
    var ele = document.createElement("strong");
    ele.className = "formErrors";
    ele.appendChild(document.createTextNode(FilterValues[input.id][num]));
    span.appendChild(ele);
  };
  
  function valueTest(input){
    var accepted = true;
    var valore = parseInt(input.value);
    if(valore == 0) {
      filterShowErr(input, 2);
      accepted = false;
    }
    if(accepted && (input.id == "minActivePrinciple" && valore > 99) || (input.id == "maxActivePrinciple" && valore > 100)) {
      filterShowErr(input, 3);
      accepted = false;
    } 
    if(accepted && input.id == "minActivePrinciple") {
      var maxAp = document.getElementById("maxActivePrinciple");
      maxAp = parseInt(maxAp.value);
      if (valore > maxAp) {
        filterShowErr(input, 4);
        accepted = false;
      }
    }
    if(accepted && input.id == "minPrice") {
      var maxPr = document.getElementById("maxPrice");
      maxPr = parseInt(maxPr.value);
      if (valore > maxPr) {
        filterShowErr(input, 3);
        accepted = false;
      }
    }
    return accepted;
  };
  
  function filterControl(input) {
    var span = selectCorrectSpan(input);
    if (span.children.length == 1) {
        span.removeChild(span.children[0]);
    }
    var filterRegex = FilterValues[input.id][0];
    if (input.value.search(filterRegex) != 0) {
      filterShowErr(input, 1);
      return false;
    } else {
      return valueTest(input);
    }
  };
  
  function validateFilters(){
    var checkFilters = true;
    for(var key in FilterValues){
      var input = document.getElementById(key); 
      var res = filterControl(input);
      checkFilters = checkFilters && res;
    }
    return checkFilters;
  };

 function loadMenu() {
    var menuContenitor = document.getElementById("navigation");
    var navigationMenu = document.getElementById("navigationMenu");
    if(window.innerWidth <= 767) {
        var but = document.getElementById("mobileMenu");
        if (!but) {
            var button = document.createElement("button");
            button.id = "mobileMenu";
            button.className = "fa fa-bars";
            button.onclick = function() {mobileMenu();};
            menuContenitor.insertBefore(button, navigationMenu);
            navigationMenu.style.marginTop = "0.5em";
            navigationMenu.style.borderTop = "1px solid #DDD";
            navigationMenu.style.display = "none";
        }
        if(navigationMenu.style.display == "block") {
            navigationMenu.style.display = "none";
        }
    } else {
        var button = document.getElementById("mobileMenu");
        if (button) {
            menuContenitor.removeChild(button);
        }
        navigationMenu.style.marginTop = "0";
        navigationMenu.style.borderTop = "transparent";
        navigationMenu.style.display = "block";
    }
}

window.onresize = loadMenu;

function mobileMenu() {
    var menu = document.getElementById("navigationMenu");
    var button = document.getElementsByClassName("mobileMenu");
    if(menu.style.display == "block") {
        menu.style.display = "none";
        button.className = "fa fa-bars";
    } else {
        menu.style.display = "block";
        button.className = "fa fa-close";
    }
};