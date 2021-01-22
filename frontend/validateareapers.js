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
