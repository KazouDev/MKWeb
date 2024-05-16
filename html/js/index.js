/* Gestion buton découvrir plus / Voir moins des logements */

let btn_decouvrir = document.getElementById("decouvrir_plus");
let btn_decouvrir_moins = document.getElementById("decouvrir_moins");
let nos_logements = document.getElementById("nos_logements");

btn_decouvrir.addEventListener("click", function() {
    var elements_caches = document.querySelectorAll(".card__container_cacher");
    elements_caches.forEach(function(element) {
        element.style.display = "flex";
    });
    btn_decouvrir.style.display = "none";
    btn_decouvrir_moins.style.display = "block";
});

btn_decouvrir_moins.addEventListener("click", function() {
    var elements_caches = document.querySelectorAll(".card__container_cacher");
    elements_caches.forEach(function(element) {
        element.style.display = "none";
    });
    btn_decouvrir.style.display = "block";
    btn_decouvrir_moins.style.display = "none";
    nos_logements.scrollIntoView({ behavior: 'smooth' });
});


/* Gestion buton découvrir plus / Voir moins des coups de coeurs */

let btn_decouvrir_coeur = document.getElementById("decouvrir_plus_coup_coeur");
let btn_decouvrir_coeur_moins = document.getElementById("decouvrir_plus_coup_coeur_moins");
let nos_coups_coeur = document.getElementById("nos_coups_coeur");

btn_decouvrir_coeur.addEventListener("click", function() {
    var elements_caches = document.querySelectorAll(".coups-coeur__container_cacher");
    elements_caches.forEach(function(element) {
        element.style.display = "flex";
    });
    btn_decouvrir_coeur.style.display = "none";
    btn_decouvrir_coeur_moins.style.display = "block";
});

btn_decouvrir_coeur_moins.addEventListener("click", function() {
    var elements_caches = document.querySelectorAll(".coups-coeur__container_cacher");
    elements_caches.forEach(function(element) {
        element.style.display = "none";
    });
    btn_decouvrir_coeur.style.display = "block";
    btn_decouvrir_coeur_moins.style.display = "none";
    nos_coups_coeur.scrollIntoView({ behavior: 'smooth' });
});