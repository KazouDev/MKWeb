"use strict";
window.addEventListener("DOMContentLoaded", (event) => {
    //menu utilisateur
    const headerUser = document.getElementById("header__info");
    const menu = document.getElementById("menu-user");
    var fermerMenu = document.getElementById("fermerMenu");
    //menu utilisateur
        headerUser.addEventListener("click", function() {
            console.log("Clicked on header user");
            menu.style.display = (menu.style.display === "none" || menu.style.display === "") ? "inline-flex" : "none";
        });
        if (fermerMenu && menu) {
            fermerMenu.addEventListener("click", function() {
                menu.style.display = "none";
            });
        }
});
