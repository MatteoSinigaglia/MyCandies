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
    "address": ["Inserisci via", /^[a-zA-Z]{3}\s[a-zA-Z]+(\s[a-zA-Z])*$/, "Indirizzo non corretto, deve iniziare per 'via'."],
    "address_number": ["Inserisci civico", /^[0-9]{1,3}([a-zA-Z]?)$/, "Civico non corretto."],
    "city": ["Inserisci città", /^([A-Z][a-zàèìòù]{2,20}\s?)+$/, "Comune non corretto. Le parole che compongono il comune devono iniziare per maiuscola."],
    "area": ["Inserisci provincia", /^[A-Z]{2}$/, "Provincia non corretta. Inserire i caratteri maiuscoli."],
    "cap": ["Inserisci CAP", /^\d{5}$/, "CAP non corretto. Il CAP è una sequenza numerica di 5 valori."],
    "telefono": ["Inserisci cellulare", /^\d{10}$/, "Cellulare non corretto. Il numero deve iniziare con la cifra '3'."]
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

function loadFormCliente() {
    for(var i in form_login) {
        var input = document.getElementById(i);
        defaultLoginValue(input);
        input.onfocus = function() { noDefaultLogin(this); };
        input.onblur = function() { defaultLoginValue(this); };
    }

    for(var i in form_registrazione) {
        var input = document.getElementById(i);
        defaultRegistrationValue(input);
        input.onfocus = function() {noDefaultRegistration(this);};
        input.onblur = function() {defaultRegistrationValue(this);};
    }
};

function removeErrors(input) {
    var parent = input.parentNode;
    if(parent.children.length == 2) {
        parent.removeChild(parent.children[1]);
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
    removeErrors(input);
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

function printRegError(input, num) {
    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "formErrors";
    element.appendChild(document.createTextNode(form_registrazione[input.id][num]));
    parent.appendChild(element);
};

function printRightPassword(input) {
    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "rightMessage";
    element.appendChild(document.createTextNode("Le password corrispondono"));
    parent.appendChild(element);
};

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
                printRegError(input, 4);
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
                    printRegError(input, 3);
                    return false;
                }
                return true;
            }
        } 
        case "address": {
            var comp = input.value.split(" ");
            if(comp[0].toLowerCase() == "via") {
                return true;
            } else {
                printRegError(input, 2);
                return false;
            }
        }
        case "confirmPassword": {
            var psw = document.getElementById('password');
            if(input.value != psw.value) {
                printRegError(input, 2);
                return false;
            } else {
                printRightPassword(input);
                return true;
            }
        }
        case "telefono": {
            if(input.value.charAt(0) != 3) {
                printRegError(input, 2);
                return false;
            } else {
                return true;
            }
        }
    }
};

function validateRegField(input) {
    removeErrors(input);
    var regex = form_registrazione[input.id][1];
    if(input.value.search(regex) != 0) {
        printRegError(input, 2);
        return false;
    } else {
        if(input.id == "birthDate" || input.id == "address" || input.id == "confirmPassword" || input.id == "telefono") {
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
        setDefaultInsertion(input);
        input.onfocus = function() { noDefaultInsertion(this); };
        input.onblur = function() { setDefaultInsertion(this); };
    }
};

function setDefaultInsertion(input) {    
    if(input.value == "") {
        input.className = "default-text";
        input.value = form_inserisciProdotto[input.id][0];
    }
};

function noDefaultInsertion(input) {
    if(input.value == form_inserisciProdotto[input.id][0]) {
        input.className = "";
        input.value = "";
    }
};

function printInsertionError(input) {  
    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "formErrors";
    element.appendChild(document.createTextNode(form_inserisciProdotto[input.id][2]));
    parent.appendChild(element);
};

function validateInsertionField(input) {
    removeErrors(input);
    var regex = form_inserisciProdotto[input.id][1];
    if(input.value.search(regex) != 0) {
        printInsertionError(input);
        return false;
    } else {
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
    "changePassword": ["Inserisci nuova password", /.{4,20}/, "Password non valida. La lunghezza deve essere tra 4 e 20 caratteri."],
    "confirmNewPassword": ["Inserisci password di conferma", /.{4,20}/, "La password non corrisponde."]
};

function defaultChangeCredential(input) {
    if(input.value == "") {
        input.className = "default-text";
        input.value = form_credenziali[input.id][0];
    }
};

function noDefaultChangeCredential(input) {
    if(input.value == form_credenziali[input.id][0]) {
        input.className = "";
        input.value = "";
    }
};

function loadChangeCredential() {
    for(var key in form_credenziali) {
        var input = document.getElementById(key);
        defaultChangeCredential(input);
        input.onfocus = function() { noDefaultChangeCredential(this); };
        input.onblur = function() { defaultChangeCredential(this); };
    }
};

function printChangeCredentialError(input) {
    var parent = input.parentNode;
    var element = document.createElement("strong");
    element.className = "formErrors";
    element.appendChild(document.createTextNode(form_credenziali[input.id][2]));
    parent.appendChild(element);
};

function validateChangeCredentialField(input) {
    removeErrors(input);
    var regex = form_credenziali[input.id][1];
    if(input.value.search(regex) != 0) {
        printChangeCredentialError(input);
        return false;
    } else {
        if(input.id == "confirmNewPassword") {
            var confronto = document.getElementById("changePassword");
            if(input.value != confronto.value) {
                printChangeCredentialError(input);
                return false;
            }
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
    "indirizzo": [/^[a-zA-Z]{3}\s[a-zA-Z]+(\s[a-zA-Z])*$/, "Indirizzo non valido. Inserire l'indirizzo di residenza."],
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

case "indirizzo": {
    var comp = input.value.split(" ");
    if(comp[0].toLowerCase() == "via") {
        return true;
    } else {
        PAShowErr(input, 1);
        return false;
    }
    }
}
};

function PAFieldValidate(input) {

var parent = input.parentNode;
if (parent.children.length == 2) {
    parent.removeChild(parent.children[1]);
}
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

/*
=========== CHECK FILTRI
*/

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