function loadMenu() {
    if(window.screen.width <= 767) {
        var menuButton = document.getElementById("mobileMenu_hidden");
        menuButton.id = "mobileMenu_visible";
        var menu = document.getElementById("navigationMenu");
        menu.style.display = "none";
    }
}

function mobileMenu() {
    var menu = document.getElementById("navigationMenu");
    var button = document.getElementsByClassName("fa fa-bars");
    if(menu.style.display == "block") {
        menu.style.display = "none";
        button.className = "fa fa-bars";
    } else {
        menu.style.display = "block";
        button.className = "fa fa-close";
    }
};