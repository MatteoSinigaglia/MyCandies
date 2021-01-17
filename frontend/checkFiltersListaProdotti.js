var FilterValues = {
  "minActivePrinciple": [/^\d{1,3}$/,"Valore non valido. Inserire un numero a max. 3 cifre.", "Valore minimo troppo basso.", "Valore minimo troppo alto.", "Il valore minimo è più grande del valore massimo."],
  "maxActivePrinciple": [/^\d{1,3}$/,"Valore non valido. Inserire un numero a max. 3 cifre.", "Valore massimo troppo basso.", "Valore massimo troppo alto."],
  "minPrice": [/^\d{1,4}$/,"Prezzo non valido.", "Prezzo minimo troppo basso.", "Il valore minimo è più grande del valore massimo."],
  "maxPrice": [/^\d{1,4}$/,"Prezzo non valido.", "Prezzo massimo troppo basso."]
};

function filterShowErr(input, num) {
  var span;
  if(input.id == "minActivePrinciple" || input.id == "maxActivePrinciple") {
    span = document.getElementById("AC_errors");
  } else {
    span = document.getElementById("PR_errors");
  }
  var ele = document.createElement("strong");
  ele.className = "formErrors";
  ele.appendChild(document.createTextNode(FilterValues[input.id][num]));
  span.appendChild(ele);
};

function valueTest(input){

  if(input.value == 0) {
    filterShowErr(input, 2);
    return false;
  }
  if((input.id == "minActivePrinciple" && input.value > 99) || (input.id == "maxActivePrinciple" && input.value > 100)) {
    filterShowErr(input, 3);
    return false;
  } 
  if(input.id == "minActivePrinciple" && input.value > FilterValues["maxActivePrinciple"].value) {
    filterShowErr(input, 4);
    return false;
  }
  if(input.id == "minPrice" && input.value > FilterValues["maxPrice"].value) {
    filterShowErr(input, 3);
    return false;
  }
};

function filterControl(input) {
  var span;
  if(input.id == "minActivePrinciple" || input.id == "maxActivePrinciple") {
    span = document.getElementById("AC_errors");
  } else {
    span = document.getElementById("PR_errors");
  }
  if (span.children.length == 1) {
    parent.removeChild(parent.children[0]);
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
    checkFilters = checkFilters && filterControl(input);
    if(!checkFilters) return false;
  }
  return checkFilters;
};
