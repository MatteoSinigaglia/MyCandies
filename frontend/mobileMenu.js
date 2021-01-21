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
        if (navigationMenu.style.display == "none") {
            navigationMenu.style.marginTop = "0";
            navigationMenu.style.borderTop = "transparent";
            navigationMenu.style.display = "block";
        }
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