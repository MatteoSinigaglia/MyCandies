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