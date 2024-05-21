let tri = {
    note : function(listeLogement, tri) {
        let liste = new Array();
        let listeID = new Array();
        let e;

        function sortAsc(listeID) {
            let e;
            let minimun;
            for (let i = 0; i < listeLogement.length; i++) {
                e = listeLogement[i];
                if (!listeID.includes(e.id_logement) && (minimun === undefined || e.note < minimun.note)) {
                    minimun = e;
                }
            }
            return minimun;
        }

        function sortDesc(listeID) {
            let e; 
            let maximun;
            for (let i = 0; i < listeLogement.length; i++) {
                e = listeLogement[i];
                if (!listeID.includes(e.id_logement) && (maximun === undefined || e.note > maximun.note)) {
                    maximun = e;
                }
            }
            return maximun;
        }
        
        if (tri === "t_asc") {
            while (liste.length < listeLogement.length) {
                e = sortAsc(listeID);
                liste.push(e);
                listeID.push(e.id_logement);
            }
        }
        else if (tri === "t_desc") {
            while (liste.length < listeLogement.length) {
                e = sortDesc(listeID);
                liste.push(e);
                listeID.push(e.id_logement);
            }
        }
        
        return liste; 
    }
}

let genererCard = {
    card_logements : function (i, logement) {
        let divCard; 
        let imgCouverture; 
        let divDescription;
        let divTitre; 
        let titre; 
        let divNote; 
        let iNote; 
        let note;
        let localisation;
        let divPrix;
        let prix; 
        let prixpar;
    
        divCard = document.createElement("div");

        if (i < 6) {
            divCard.classList.add("card__container");
        } else {
            divCard.classList.add("card__container");
            divCard.classList.add("card__container_cacher");
        }

        imgCouverture = document.createElement("img"); 
        imgCouverture.setAttribute("src", "img/logement1.webp");
        imgCouverture.setAttribute("alt", logement.titre); 

        divDescription = document.createElement("div");
        divDescription.classList.add("logement__wrapper_description");
        
        divTitre = document.createElement("div");
        divTitre.classList.add("logement__titre");
        
        titre = document.createElement("h1");
        titre.classList.add("logement__nom");
        titre.id = "logement__nom";
        titre.innerHTML = logement.titre;

        divNote = document.createElement("div");
        divNote.classList.add("logement__note");
        
        iNote = document.createElement("i");
        iNote.classList.add("fas");
        iNote.classList.add("fa-star");
        iNote.classList.add("fa-sm");
        
        note = document.createElement("h1");
        note.classList.add("logement__note");
        note.innerHTML = logement.note;
        
        localisation = document.createElement("h1");
        localisation.classList.add("logement__localisation");
        localisation.innerHTML = logement.commune + ", " + logement.departement; 

        divPrix = document.createElement("div");
        divPrix.classList.add("logement__prix__alignement"); 
        
        prix = document.createElement("h1");
        prix.classList.add("logement__prix");
        prix.innerHTML = logement.tarif + "€";
        
        prixpar = document.createElement("h1");
        prixpar.classList.add("logement__localisation");
        prixpar.innerHTML = "/jour";
        
        divNote.appendChild(iNote); 
        divNote.appendChild(note);

        divTitre.appendChild(titre);
        divTitre.appendChild(divNote);

        divPrix.appendChild(prix);
        divPrix.appendChild(prixpar);
        
        divDescription.appendChild(divTitre);
        divDescription.appendChild(localisation);
        divDescription.appendChild(divPrix); 

        divCard.appendChild(imgCouverture);
        divCard.appendChild(divDescription);

        return divCard; 
    }, 

    card_coups : function (i, logement) {
        let divCard; 
        let imgCouverture; 
        let divDescription;
        let divTitre; 
        let titre; 
        let tarif; 
        let span; 
        let divNote; 
        let div;
        let iNote; 
        let note;
        let localisation;
    
        divCard = document.createElement("div");

        if (i < 4) {
            divCard.classList.add("coups-coeur__container");
        } else {
            divCard.classList.add("coups-coeur__container");
            divCard.classList.add("coups-coeur__container_cacher");
        }
        
        imgCouverture = document.createElement("img");
        imgCouverture.setAttribute("src", "img/logement1.webp");
        imgCouverture.setAttribute("alt", logement.titre);
        
        divDescription = document.createElement("div");

        divTitre = document.createElement("div");
        divTitre.classList.add("coups-coeur__titre");
        
        titre = document.createElement("h1");
        titre.innerHTML = logement.titre;
        
        tarif = document.createElement("h2");
        span = document.createElement("span");
        span.innerHTML = logement.tarif + "€";
        
        localisation = document.createElement("h1");
        localisation.innerHTML = logement.commune + ", " + logement.departement;

        divNote = document.createElement("div");
        divNote.classList.add("coups-coeur__note");
        
        div = document.createElement("div");
        for (let i = 0 ; i < 5 ; i++) {
            iNote = document.createElement("i");
            iNote.classList.add("fas");
            iNote.classList.add("fa-star");
            iNote.classList.add("fa-sm");
            div.appendChild(iNote);
        }

        note = document.createElement("h1");
        note.innerHTML = logement.note;
        
        tarif.appendChild(span);
        tarif.append("/jour");

        divTitre.appendChild(titre);
        divTitre.appendChild(tarif);

        divNote.appendChild(div);
        divNote.appendChild(note);

        divDescription.appendChild(divTitre); 
        divDescription.appendChild(localisation);
        divDescription.appendChild(divNote); 

        divCard.appendChild(imgCouverture);
        divCard.appendChild(divDescription);

        return divCard;
    }
}

function php_genererListeDepartement() {
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: { action: "genererListeDepartement" },
        dataType: 'json',
        success: function(reponse) {
            if (reponse.reponse) {
                let listeDepartement = reponse.reponse;
                
                let divDepartement = document.getElementById("departments-dropdown");
                let label; 
                let input;
                let textNode;

                let communesDropdown = document.getElementById('communes-dropdown');
                
                for (let i = 0; i < listeDepartement.length; i++) {
                    label = document.createElement("label");
                    label.classList.add("dropdown-element");
                    label.classList.add("dep");

                    input = document.createElement("input");
                    input.type = "checkbox";
                    input.name = "department";
                    input.value = listeDepartement[i].departement;

                    textNode = document.createTextNode(listeDepartement[i].departement)
                    
                    label.appendChild(input);
                    label.appendChild(textNode);
                    divDepartement.appendChild(label);
                    
                    input.addEventListener('change', function() {
                        communesDropdown.style.display = this.checked ? 'inline-block' : 'none';
                        php_genererListeCommune(listeDepartement[i].departement); 
                    });
                }
            }
        }
    });
}

function php_genererListeCommune(departement) {
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: { action: "genererListeCommune", departement: departement },
        dataType: 'json',
        success: function(reponse) {
            if (reponse.reponse) {
                let listeCommune = reponse.reponse;
                
                let divCommune = document.getElementById("communes-dropdown");
                divCommune.innerHTML = "";

                let inputSearch = document.createElement("input");
                inputSearch.classList.add("dropdown-element");
                inputSearch.classList.add("search");
                inputSearch.setAttribute("type", "text"); 
                inputSearch.setAttribute("id", "search-input-commune");
                inputSearch.setAttribute("placeholder", "Commune");

                divCommune.appendChild(inputSearch);

                let label; 
                let input;
                let textNode;
                
                for (let i = 0; i < listeCommune.length; i++) {
                    label = document.createElement("label");
                    label.classList.add("dropdown-element");
                    label.classList.add("dep");

                    input = document.createElement("input");
                    input.type = "checkbox";
                    input.name = "commune";
                    input.value = listeCommune[i].commune;

                    textNode = document.createTextNode(listeCommune[i].commune)
                    
                    label.appendChild(input);
                    label.appendChild(textNode);
                    divCommune.appendChild(label);
                }
            }
        }
    });
}

function php_genererSelectProprietaire() {
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: { action: "genererSelectProprietaire" },
        dataType: 'json',
        success: function(reponse) {
            if (reponse.reponse) {
                let listeProprietaire = reponse.reponse;

                let selectProprietaire = document.getElementById("propr-select");
                let optionProprietaire;
                
                for (let i = 0; i < listeProprietaire.length; i++) {
                    optionProprietaire = document.createElement("option");
                    optionProprietaire.setAttribute("value", listeProprietaire[i].id);
                    optionProprietaire.innerHTML = listeProprietaire[i].nom + " " + listeProprietaire[i].prenom;
                    selectProprietaire.appendChild(optionProprietaire);
                }
            }
        }
    });
}

function php_genererListeLogement() {
    let t_note = document.getElementById('tri-note-value').textContent;

    let f_nb_personnes = $('#nb_personnes').val();
    let f_tarif_min = $('#tarif_min').val();
    let f_tarif_max = $('#tarif_max').val();
    let f_proprietaire = $('#propr-select').val();
    
    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: { action: "genererListeLogement", nb_personnes: f_nb_personnes, 
                tarif_min: f_tarif_min, tarif_max : f_tarif_max, proprietaire: f_proprietaire },
        dataType: 'json',
        success: function(reponse) {
            if (reponse.reponse) {
                let listeLogements = reponse.reponse;
                let logement; 

                let divLogementVide = document.getElementById('les__logements__vide');

                if (listeLogements.length <= 0) {
                    divLogementVide.style.display = "flex";
                }
                else {
                    divLogementVide.style.display = "none";

                    /* construction categorie "Nos logements" */
                    if (t_note != "t_init") {
                        listeLogements = tri.note(listeLogements, t_note);
                    }

                    let divLogements = document.getElementById("les__logements"); 
                    divLogements.innerHTML = "";

                    for (let i = 0; i < listeLogements.length; i++) {
                        logement = listeLogements[i];
                    
                        divLogements.appendChild(genererCard.card_logements(i, logement));
                    }
                }
            }
        }
    });
}

function php_genererListeCoupsCoeur() {
    let f_nb_personnes = $('#nb_personnes').val();
    let f_tarif_min = $('#tarif_min').val();
    let f_tarif_max = $('#tarif_max').val();
    let f_proprietaire = $('#propr-select').val();

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: { action: "genererListeLogement", nb_personnes: f_nb_personnes, 
                tarif_min: f_tarif_min, tarif_max : f_tarif_max, proprietaire: f_proprietaire },
        dataType: 'json',
        success: function(reponse) {
            if (reponse.reponse) {
                let listeLogements = reponse.reponse;
                let logement; 

                /* construction categorie "Nos coups de coeur" */
                listeLogements = tri.note(listeLogements, "t_desc");

                let divCoups = document.getElementById("les__coups"); 
                divCoups.innerHTML = "";
                
                let nb_card = 0; 
                for (let i = 0; i < listeLogements.length; i++) {
                    logement = listeLogements[i];

                    if (logement.note >= 3.5) {
                        divCoups.appendChild(genererCard.card_coups(nb_card, logement));
                        nb_card++; 
                    }
                }
            }
        }
    });
}

$(document).ready(function() {
    /* Appel de les fonctions au chargement de la page */
    php_genererListeDepartement();
    php_genererSelectProprietaire();

    php_genererListeLogement();
    php_genererListeCoupsCoeur();

    /* Appel de la fonction php_genererListeCommune au clic sur un département*/


    /* Appel des fonctions php_genererListeLogement et php_genererListeCoupsCoeur au clic sur le bouton Recherche */
    $('#executeRecherche').click(function() {
        php_genererListeLogement();
        php_genererListeCoupsCoeur();
    });

    /* Appel de la fonction php_genererListeLogement au clic sur la tri */
    document.getElementById('tri_note').onclick = function() {
        let mode = document.getElementById('tri-note-value')
        if (mode.textContent === "t_init") {
            mode.textContent = "t_asc";
        }
        else if (mode.textContent === "t_asc") {
            mode.textContent = "t_desc";
        }
        else if (mode.textContent === "t_desc") {
            mode.textContent = "t_init";
        }
        php_genererListeLogement();
    };
});





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