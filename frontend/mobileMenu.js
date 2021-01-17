function modifyStylesheet() {
    var head = document.head;
    var link = document.getElementById("mobileStyleSheet");
    link.href = "../frontend/style_mobile.css";
    head.appendChild(link);
};

function setMenuVisible() {
    var menu = document.getElementById("navigationMenu");
    menu.style.display = "block";
};

function mobileMenu() {
    var menu = document.getElementById("navigationMenu");
    var button = document.getElementById("mobileMenu");
    if(menu.style.display === "block") {
        menu.style.display = "none";
        button.className = "fa fa-bars";
    } else {
        menu.style.display = "block";
        button.className = "fa fa-close";
    }
};