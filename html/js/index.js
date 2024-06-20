let tri = {
  
};

let genererCard = {
  card_logements: function (i, logement) {
    let divCard;
    let imgCouverture;
    let divDescription;
    let divTitre;
    let titre;
    let localisation;
    let divPrix;
    let prix;
    let prixpar;
    let aLien;
    
    aLien=document.createElement("a");
    aLien.href="./detail_logement.php?id="+logement.id_logement;
    if (i >= 6) {
      aLien.classList.add("card__cont_cacher");
    } 
    divCard = document.createElement("div");

    divCard.classList.add("card__cont");

    imgCouverture = document.createElement("img");
    imgCouverture.setAttribute("src", "../img" + logement.image_src);
    imgCouverture.setAttribute("alt", logement.image_alt);

    divDescription = document.createElement("div");
    divDescription.classList.add("logement__wrapper_description");

    divTitre = document.createElement("div");
    divTitre.classList.add("logement__titre");

    titre = document.createElement("h1");
    titre.classList.add("logement__nom");
    titre.id = "logement__nom";
    titre.innerHTML = logement.titre;

    localisation = document.createElement("h1");
    localisation.classList.add("logement__localisation");
    localisation.innerHTML = logement.commune + ", " + logement.departement;

    divPrix = document.createElement("div");
    divPrix.classList.add("logement__prix__alignement");

    prix = document.createElement("h1");
    prix.classList.add("logement__prix");
    prix.innerHTML = logement.tarif + " €";

    prixpar = document.createElement("h1");
    prixpar.classList.add("logement__localisation");
    prixpar.innerHTML = "/jour";

    divTitre.appendChild(titre);

    divPrix.appendChild(prix);
    divPrix.appendChild(prixpar);

    divDescription.appendChild(divTitre);
    divDescription.appendChild(localisation);
    divDescription.appendChild(divPrix);

    divCard.appendChild(imgCouverture);
    divCard.appendChild(divDescription);

    aLien.appendChild(divCard);

    return aLien;
  },
};

function php_genererAutocompletProprietaire() {
  $.ajax({
    url: "ajax/index.ajax.php",
    type: "POST",
    data: { action: "genererSelectProprietaire" },
    dataType: "json",
    success: function (reponse) {
      if (reponse.reponse) {
        let listeProprietaire = reponse.reponse;

        let f_propri = document.getElementById("filtre-propri-id");
        let proprietaireInput = document.getElementById("proprietaireInput");
        let autocompleteList = document.getElementById("autocomplete-list-proprietaire");

        // Fonction pour filtrer les suggestions
        let filterSuggestions = () => {
          let inputValue = proprietaireInput.value.trim().toLowerCase();
          autocompleteList.innerHTML = "";

          if (!inputValue) return;

          let filteredProprietaires = listeProprietaire.filter(
            (proprietaire) =>
              proprietaire.nom.toLowerCase().includes(inputValue) ||
              proprietaire.prenom.toLowerCase().includes(inputValue)
          );

          filteredProprietaires.forEach((proprietaire) => {
            let suggestionItem = document.createElement("div");
            suggestionItem.classList.add("autocomplete-suggestion");
            suggestionItem.textContent = `${proprietaire.nom} ${proprietaire.prenom}`;
            suggestionItem.addEventListener("click", () => {
              f_propri.value = proprietaire.id;
              proprietaireInput.value = `${proprietaire.nom} ${proprietaire.prenom}`;
              autocompleteList.innerHTML = "";
            });
            autocompleteList.appendChild(suggestionItem);
          });
        };

        // Add event listener for input changes
        proprietaireInput.addEventListener("input", filterSuggestions);

        // Hide suggestions when clicking outside
        document.addEventListener("click", (e) => {
          if (!autocompleteList.contains(e.target) && e.target !== proprietaireInput) {
            autocompleteList.innerHTML = "";
          }
        });
      }
    },
  });
}

function php_genererListeLogement() {
  let f_nb_personnes = document.getElementById("nb_personnes").value;
  let f_tarif_min = document.getElementById("tarif_min").value;
  let f_tarif_max = document.getElementById("tarif_max").value;
  let f_codeDepartement = document.getElementById("filtre-departement-code").getAttribute("value");
  let f_codePostal = document.getElementById("filtre-commune-codePostal").getAttribute("value");

  let f_proprietaire = undefined; 
  if (document.getElementById("proprietaireInput").value !== "") {
    f_proprietaire = document.getElementById("filtre-propri-id").value;
  }

  let f_date_deb = undefined;
  let f_date_fin = undefined;
  if ($('input[name="daterange"]').val() != "") {
    f_date_deb = document.getElementById("filtre-date-deb").value;
    f_date_fin = document.getElementById("filtre-date-fin").value;
  }
  let dateArrive = new Date(f_date_deb);
  let dateDepart = new Date(f_date_fin);

  let where_req1 = "";
  if (f_nb_personnes != "") { where_req1 += " AND l.nb_max_personne >= " + f_nb_personnes; }
  if (f_tarif_min != "") { where_req1 += " AND l.base_tarif >= " + f_tarif_min; }
  if (f_tarif_max != "") { where_req1 += " AND l.base_tarif <= " + f_tarif_max; }
  if (f_proprietaire != undefined) { where_req1 += " AND l.id_proprietaire = " + f_proprietaire; }
  if (f_codeDepartement != null && f_codeDepartement != "") { where_req1 += " AND a.departement = '" + f_codeDepartement.trim() + "'"; }
  if (f_codePostal != null && f_codePostal != "") { where_req1 += " AND a.code_postal = '" + f_codePostal + "'"; }

  let where_req2 = ""; 
  dateDepart.setDate(dateDepart.getDate() + 1);
  let i = 0;
  for (let d = new Date(dateArrive) ; d < dateDepart ; d.setDate(d.getDate() + 1)) {
    let formattedDate = d.toISOString().split("T")[0];
    if (i == 0) { where_req2 += " AND (sae._calendrier.date = '" + formattedDate + "'"; } 
    else { where_req2 += " OR sae._calendrier.date = '" + formattedDate + "'"; }
    i++;
  }
  if (where_req2 !== "") { where_req2 += ")"; }

  // Afficher chargement
  document.getElementById("loading-overlay").style.display = "flex";

  $.ajax({
    url: "ajax/index.ajax.php",
    type: "POST",
    data: { action: "genererListeLogement", where_req1: where_req1, where_req2: where_req2 },
    dataType: "json",
  })
  .done(function(reponse) {
    if (reponse.reponse) {
      const nb_logement_trouve = document.getElementById("nb_logement_trouve"); 
      
      let listeLogements = reponse.reponse;
      let logement;

      let divLogements = document.getElementById("les__logements");
      divLogements.innerHTML = "";

      nb_logement_trouve.textContent = listeLogements.length;

      if (listeLogements.length > 0) {
        /* construction categorie "Nos logements" */
        for (let i = 0; i < listeLogements.length; i++) {
          logement = listeLogements[i];
          divLogements.appendChild(genererCard.card_logements(i, logement));
        }
      } else {
        let divVide = document.createElement("div");
        let pVide = document.createElement("p");
        pVide.innerHTML = "Aucun logement ne correspond à la recherche.";
        divVide.appendChild(pVide);
        divLogements.appendChild(divVide);
      }
    }
  })
  .fail(function(error) {
    console.error("Error in php_genererListeLogement:", error);
  })
  .always(function() {
    // Cacher chargement
    document.getElementById("loading-overlay").style.display = "none";
  });
}


document.addEventListener("DOMContentLoaded", () => {
  const communeInput = document.getElementById("communeInput");
  const dropdown = document.getElementById("dropdown");
  const imageContainers = document.querySelectorAll(".image-container");
  const filtreCommuneCodePostal = document.getElementById("filtre-commune-codePostal");
  const filtreDepartementCode = document.getElementById("filtre-departement-code");

  // Initialisation de l'autocomplete commune
  php_genererAutocompletCommune();
  // Initialisation de l'autocomplete propriétaire
  php_genererAutocompletProprietaire();
  
  php_genererListeLogement();

  /* Appel des fonctions au clic sur le bouton Recherche */
  $("#executeRecherche").click(function () {
    php_genererListeLogement();
  });

  
  communeInput.addEventListener("click", () => {
    dropdown.style.display = "block";
  });

  // Ajouter un événement pour réinitialiser le champ caché et communeInput si le texte est effacé
  communeInput.addEventListener("input", () => {
    if (communeInput.value.trim() === "") {
        imageContainers.forEach((cont) => cont.classList.remove("selected"));
        filtreDepartementCode.setAttribute("value", "");
        filtreCommuneCodePostal.setAttribute("value", "");
        dropdown.style.display = "block";
    } else {
        dropdown.style.display = "none";
    }
  });

  window.addEventListener("click", (event) => {
    if (event.target !== communeInput && !dropdown.contains(event.target)) {
      dropdown.style.display = "none";
    }
  });

  imageContainers.forEach((container) => {
    container.addEventListener("click", () => {
        // Retirer la classe 'selected' de toutes les autres images
        imageContainers.forEach((cont) => cont.classList.remove("selected"));

        // Ajouter la classe 'selected' à l'image cliquée
        container.classList.add("selected");

        // Mettre à jour le champ communeInput avec le nom et le code du département
        const departmentName = container.getAttribute("data-value");
        const departmentCode = container.getAttribute("data-code");
        communeInput.value = `${departmentName} (${departmentCode})`;

        // Mettre à jour le champ caché avec le code du département
        filtreDepartementCode.setAttribute("value", departmentName);

        // Réinitialiser le champ filtreCommuneCodePostal
        filtreCommuneCodePostal.setAttribute("value", "");

        // Fermer le dropdown
        dropdown.style.display = "none";
    });
  });


  // Fonction de gestion de l'autocomplete
  function php_genererAutocompletCommune() {
    // Fetch des données depuis l'API (adapter à votre API)
    let communes = [];

    fetch("https://geo.api.gouv.fr/communes?codeRegion=53")
        .then((response) => response.json())
        .then((data) => {
            communes = data;
        })
        .catch((error) => console.error("Erreur:", error));

    // Filtrage et affichage des suggestions
    communeInput.addEventListener("input", () => {
        let inputValue = communeInput.value.trim().toLowerCase();
        let filteredCommunes = communes.filter((commune) =>
            commune.nom.toLowerCase().startsWith(inputValue)
        );

        let suggestions = filteredCommunes.map((commune) => {
            return `${commune.nom} (${commune.codesPostaux.join(", ")})`;
        });

        // Affichage des suggestions
        displaySuggestions(suggestions);
    });

    // Affichage des suggestions dans l'élément autocomplete-list-commune
    function displaySuggestions(suggestions) {
        let autocompleteList = document.getElementById("autocomplete-list-commune");
        autocompleteList.innerHTML = "";

        suggestions.forEach((suggestion) => {
            let suggestionItem = document.createElement("div");
            suggestionItem.classList.add("autocomplete-suggestion");
            suggestionItem.textContent = suggestion;
            suggestionItem.addEventListener("click", () => {
                let selectedText = suggestionItem.textContent;
                let selectedCommune = communes.find((commune) =>
                    selectedText.includes(commune.nom)
                );

                if (selectedCommune) {
                    let codePostal = selectedCommune.codesPostaux[0];
                    filtreCommuneCodePostal.setAttribute("value", codePostal);
                    communeInput.value = selectedText;

                    // Réinitialiser le champ de sélection de département
                    imageContainers.forEach((cont) => cont.classList.remove("selected"));
                    filtreDepartementCode.setAttribute("value", "");

                    // Fermer le dropdown
                    dropdown.style.display = "none";
                }
            });
            autocompleteList.appendChild(suggestionItem);
        });
    }

    // Gestion de la fermeture des suggestions au clic en dehors
    document.addEventListener("click", (e) => {
        let autocompleteList = document.getElementById("autocomplete-list-commune");
        if (!autocompleteList.contains(e.target) && e.target !== communeInput) {
            autocompleteList.innerHTML = "";
        }
    });
  }

  /* Gestion buton découvrir plus / Voir moins des logements */
  let btn_decouvrir = document.getElementById("decouvrir_plus");
  let btn_decouvrir_moins = document.getElementById("decouvrir_moins");
  let nos_logements = document.getElementById("nos_logements");

  btn_decouvrir.addEventListener("click", function () {
    var elements_caches = document.querySelectorAll(".card__cont_cacher");
    elements_caches.forEach(function (element) {
      element.style.display = "flex";
    });
    btn_decouvrir.style.display = "none";
    btn_decouvrir_moins.style.display = "block";
  });

  btn_decouvrir_moins.addEventListener("click", function () {
    var elements_caches = document.querySelectorAll(".card__cont_cacher");
    elements_caches.forEach(function (element) {
      element.style.display = "none";
    });
    btn_decouvrir.style.display = "block";
    btn_decouvrir_moins.style.display = "none";
    nos_logements.scrollIntoView({ behavior: "smooth" });
  });

  /* Gestion Image tri */
  const triImage = document.getElementById('tri_image');
  const up = triImage.querySelector('.tri__up');
  const upDark = triImage.querySelector('.tri__up-dark');
  const down = triImage.querySelector('.tri__down');
  const downDark = triImage.querySelector('.tri__down-dark');

  let clickCount = 0;

  triImage.addEventListener('click', () => {
    clickCount++;
    switch (clickCount) {
      case 1:
        up.style.display = 'none';
        upDark.style.display = 'block';
        down.style.display = 'block';
        downDark.style.display = 'none';
        break;
      case 2:
        up.style.display = 'block';
        upDark.style.display = 'none';
        down.style.display = 'none';
        downDark.style.display = 'block';
        break;
      default:
        clickCount = 0;
        up.style.display = 'block';
        upDark.style.display = 'none';
        down.style.display = 'block';
        downDark.style.display = 'none';
        break;
    }
    
  });
  
});


/* Gestion du calendrier */
$(function () {
  $('input[name="daterange"]').daterangepicker(
    {
      opens: "right",
      startDate: moment(),
      autoUpdateInput: false,
      locale: {
        format: "DD/MM/YYYY",
        applyLabel: "Confirmer",
        cancelLabel: "Annuler",
        daysOfWeek: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
        monthNames: ["Janvier", "Février",  "Mars",
                     "Avril",   "Mai",      "Juin",
                     "Juillet", "Août",     "Septembre",
                     "Octobre", "Novembre", "Décembre",],
        firstDay: 1,
      },
      applyButtonClasses: "custom-apply-button",
      cancelButtonClasses: "custom-cancel-button",
    },
    function (start, end, label) {
      $('input[name="daterange"]').val(
        start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY")
      );
      document.getElementById("filtre-date-deb").value =
        start.format("YYYY-MM-DD");
      document.getElementById("filtre-date-fin").value =
        end.format("YYYY-MM-DD");
    }
  );
});

