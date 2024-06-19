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

function php_genererAutocompletCommune() {
  let f_commune = document.getElementById("filtre-commune-codePostal");
  let communeInput = document.getElementById("communeInput");
  let autocompleteList = document.getElementById("autocomplete-list-commune");

  let communes = [];

  // Fetch data from API
  fetch("https://geo.api.gouv.fr/communes?codeRegion=53")
    .then((response) => response.json())
    .then((data) => {
      communes = data;
    })
    .catch((error) => console.error("Erreur:", error));

  // Function to filter and display suggestions
  let filterSuggestions = (input) => {
    autocompleteList.innerHTML = "";
    if (!input) return;

    let filteredCommunes = communes.filter((commune) => {
      let lowerCaseInput = input.toLowerCase();
      return (
        commune.nom.toLowerCase().startsWith(lowerCaseInput) ||
        commune.codesPostaux.some((codePostal) =>
          codePostal.startsWith(lowerCaseInput)
        )
      );
    });

    filteredCommunes.forEach((commune) => {
      let suggestionItem = document.createElement("div");
      suggestionItem.classList.add("autocomplete-suggestion");
      suggestionItem.textContent = `${commune.nom} (${commune.codesPostaux.join(
        ", "
      )})`;
      suggestionItem.addEventListener("click", () => {
        f_commune.value = `${commune.codesPostaux.join(", ")}`;
        communeInput.value =
          commune.nom + " (" + commune.codesPostaux.join(", ") + ")";
        autocompleteList.innerHTML = "";
      });
      autocompleteList.appendChild(suggestionItem);
    });
  };

  // Add event listener for input changes
  communeInput.addEventListener("input", () => {
    let inputValue = communeInput.value;
    filterSuggestions(inputValue);
  });

  // Hide suggestions when clicking outside
  document.addEventListener("click", (e) => {
    if (!autocompleteList.contains(e.target) && e.target !== communeInput) {
      autocompleteList.innerHTML = "";
    }
  });
}

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
  let f_codePostal = undefined; 
  if (document.getElementById("communeInput").value !== "") {
    f_codePostal = document.getElementById("filtre-commune-codePostal").value;
  }
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
  if (f_codePostal != undefined) {
    f_codePostal = f_codePostal.split(", ");
    for (let i = 0; i < f_codePostal.length; i++) {
      if (i == 0) { where_req1 += " AND (a.code_postal = '" + f_codePostal[i] + "'"; } 
      else { where_req1 += " OR a.code_postal = '" + f_codePostal[i] + "'"; }
    }
    where_req1 += ")";
  }

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
      let listeLogements = reponse.reponse;
      let logement;

      let divLogements = document.getElementById("les__logements");
      divLogements.innerHTML = "";

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


$(document).ready(function () {
  /* Appel de les fonctions au chargement de la page */
  php_genererAutocompletCommune();
  php_genererAutocompletProprietaire();
  php_genererListeLogement();

  /* Appel des fonctions php_genererListeLogement au clic sur le bouton Recherche */
  $("#executeRecherche").click(function () {
    php_genererListeLogement();
  });
});

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

