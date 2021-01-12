function modifyStylesheet() {
    var head = document.head;
    var link = document.getElementById("mobileStyleSheet");
    link.href = "../frontend/style_mobile.css";
    head.appendChild(link);
    var carrello = document.getElementById("cartButton");
    if(window.screen.width < 768) {
        carrello.className = "fa fa-shopping-cart";
        carrello.innerHTML = "";
    } else {
        carrello.className = "buttons";
        carrello.innerHTML = "Carrello";
    }
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