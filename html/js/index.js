let listeIDLogements = [];

let filtre = {
  id: function (listeLogement) {
    let listeID = new Array();
    let liste = new Array();

    for (let i = 0; i < listeIDLogements.length; i++) {
      listeID.push(listeIDLogements[i].id_logement);
    }

    for (let i = 0; i < listeLogement.length; i++) {
      if (!listeID.includes(listeLogement[i].id_logement)) {
        liste.push(listeLogement[i]);
      }
    }

    return liste;
  },
};

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
    imgCouverture.setAttribute("src", "./img" + logement.image_src);
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
        let autocompleteList = document.getElementById(
          "autocomplete-list-proprietaire"
        );

        let filterSuggestions = (input) => {
          autocompleteList.innerHTML = "";
          if (!input) return;

          let lowerCaseInput = input.toLowerCase();
          let filteredProprietaires = listeProprietaire.filter(
            (proprietaire) =>
              proprietaire.nom.toLowerCase().startsWith(lowerCaseInput) ||
              proprietaire.prenom.toLowerCase().startsWith(lowerCaseInput)
          );

          filteredProprietaires.forEach((proprietaire) => {
            let suggestionItem = document.createElement("div");
            suggestionItem.classList.add("autocomplete-suggestion");
            suggestionItem.textContent = `${proprietaire.nom} ${proprietaire.prenom}`;
            suggestionItem.addEventListener("click", () => {
              f_propri.value = `${proprietaire.id}`;
              proprietaireInput.value = `${proprietaire.nom} ${proprietaire.prenom}`;
              autocompleteList.innerHTML = "";
            });
            autocompleteList.appendChild(suggestionItem);
          });
        };

        // Add event listener for input changes
        proprietaireInput.addEventListener("input", () => {
          let inputValue = proprietaireInput.value;
          filterSuggestions(inputValue);
        });

        // Hide suggestions when clicking outside
        document.addEventListener("click", (e) => {
          if (
            !autocompleteList.contains(e.target) &&
            e.target !== proprietaireInput
          ) {
            autocompleteList.innerHTML = "";
          }
        });
      }
    },
  });
}

function genererPeriodePourListeLogement(listeLogement) {
  let f_date_deb = undefined;
  let f_date_fin = undefined;

  if ($('input[name="daterange"]').val() != "") {
    f_date_deb = document.getElementById("filtre-date-deb").value;
    f_date_fin = document.getElementById("filtre-date-fin").value;
  }

  let where = "";
  if (f_date_deb !== undefined && f_date_fin !== undefined) {
    for (let i = 0; i < listeLogement.length; i++) {
      if (i == 0) {
        where +=
          " AND (sae._calendrier.id_logement = " + listeLogement[i].id_logement;
      } else {
        where +=
          " OR sae._calendrier.id_logement = " + listeLogement[i].id_logement;
      }
    }
    if (where !== "") {
      where += ")";
    }

    let dateArrive = new Date(f_date_deb);
    let dateDepart = new Date(f_date_fin);
    dateDepart.setDate(dateDepart.getDate() + 1);
    let i = 0;
    for (
      let d = new Date(dateArrive);
      d < dateDepart;
      d.setDate(d.getDate() + 1)
    ) {
      let formattedDate = d.toISOString().split("T")[0];
      if (i == 0) {
        where += " AND (sae._calendrier.date = '" + formattedDate + "'";
      } else {
        where += " OR sae._calendrier.date = '" + formattedDate + "'";
      }
      i++;
    }
    if (where !== "") {
      where += ")";
    }
  }

  return new Promise((resolve, reject) => {
    $.ajax({
      url: "ajax/index.ajax.php",
      type: "POST",
      data: { action: "genererPeriodePourListeLogement", where: where },
      dataType: "json",
      success: function (reponse) {
        if (reponse.reponse) {
          listeIDLogements = reponse.reponse;
          resolve();
        }
      },
    });
  });
}

async function php_genererListeLogement() {
  let f_nb_personnes = document.getElementById("nb_personnes").value;
  let f_tarif_min = document.getElementById("tarif_min").value;
  let f_tarif_max = document.getElementById("tarif_max").value;
  let f_codePostaux = document.getElementById("filtre-commune-codePostal").value;
  let f_proprietaire = document.getElementById("filtre-propri-id").value;

  let where = "";
  if (f_nb_personnes != "") { where += " AND l.nb_max_personne >= " + f_nb_personnes; }
  if (f_tarif_min != "") { where += " AND l.base_tarif >= " + f_tarif_min; }
  if (f_tarif_max != "") { where += " AND l.base_tarif <= " + f_tarif_max; }
  if (f_proprietaire != undefined) { where += " AND l.id_proprietaire = " + f_proprietaire; }
  if (f_codePostaux != undefined) { 
    f_codePostaux = f_codePostaux.split(", ");
    for (let i = 0; i < f_codePostaux.length; i++) {
      if (i == 0) { where += " AND (a.code_postal = '" + f_codePostaux[i] + "'"; } 
      else { where += " OR a.code_postal = '" + f_codePostaux[i] + "'"; }
    }
    where += ")";
  }

  try {
    let reponse = await $.ajax({
      url: "ajax/index.ajax.php",
      type: "POST",
      data: { action: "genererListeLogement", where: where },
      dataType: "json",
    });

    if (reponse.reponse) {
      let listeLogements = reponse.reponse;
      let logement;

      await genererPeriodePourListeLogement(listeLogements);

      if (listeIDLogements != undefined) {
        listeLogements = filtre.id(listeLogements);
      }

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
  } catch (error) {
    console.error("Error in php_genererListeLogement:", error);
  }
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
