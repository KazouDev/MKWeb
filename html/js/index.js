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
  note: function (listeLogement, tri) {
    let liste = new Array();
    let listeID = new Array();
    let e;

    function sortAsc(listeID) {
      let e;
      let minimun;
      for (let i = 0; i < listeLogement.length; i++) {
        e = listeLogement[i];
        if (
          !listeID.includes(e.id_logement) &&
          (minimun === undefined || e.note < minimun.note)
        ) {
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
        if (
          !listeID.includes(e.id_logement) &&
          (maximun === undefined || e.note > maximun.note)
        ) {
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
    } else if (tri === "t_desc") {
      while (liste.length < listeLogement.length) {
        e = sortDesc(listeID);
        liste.push(e);
        listeID.push(e.id_logement);
      }
    }

    return liste;
  },
};

let genererCard = {
  card_logements: function (i, logement) {
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
    let aLien;
    
    aLien=document.createElement("a");
    aLien.href="./detail_logement.php?id="+logement.id_logement;
    
    divCard = document.createElement("div");

    if (i < 6) {
      divCard.classList.add("card__container");
    } else {
      divCard.classList.add("card__container");
      divCard.classList.add("card__container_cacher");
    }

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
    prix.innerHTML = logement.tarif + " €";

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

    aLien.appendChild(divCard);

    return aLien;
  },

  card_coups: function (i, logement) {
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
    let aLien;
    
    aLien=document.createElement("a");
    aLien.href="./detail_logement.php?id="+logement.id_logement;

    divCard = document.createElement("div");

    if (i < 4) {
      divCard.classList.add("coups-coeur__container");
    } else {
      divCard.classList.add("coups-coeur__container");
      divCard.classList.add("coups-coeur__container_cacher");
    }

    imgCouverture = document.createElement("img");
    imgCouverture.setAttribute("src", "./img" + logement.image_src);
    imgCouverture.setAttribute("alt", logement.image_alt);

    divDescription = document.createElement("div");

    divTitre = document.createElement("div");
    divTitre.classList.add("coups-coeur__titre");

    titre = document.createElement("h1");
    titre.innerHTML = logement.titre;

    tarif = document.createElement("h2");
    span = document.createElement("span");
    span.innerHTML = logement.tarif + " €";

    localisation = document.createElement("h1");
    localisation.innerHTML = logement.commune + ", " + logement.departement;

    divNote = document.createElement("div");
    divNote.classList.add("coups-coeur__note");

    div = document.createElement("div");
    for (let i = 0; i < 5; i++) {
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
  let t_note = document.getElementById("tri-note-value").textContent;

  let f_nb_personnes = document.getElementById("nb_personnes").value;
  let f_tarif_min = document.getElementById("tarif_min").value;
  let f_tarif_max = document.getElementById("tarif_max").value;
  let f_codePostaux = document.getElementById(
    "filtre-commune-codePostal"
  ).value;
  let f_proprietaire = document.getElementById("filtre-propri-id").value;

  let where = "";
  if (f_nb_personnes != "") {
    where += " AND l.nb_max_personne >= " + f_nb_personnes;
  }
  if (f_tarif_min != "") {
    where += " AND l.base_tarif >= " + f_tarif_min;
  }
  if (f_tarif_max != "") {
    where += " AND l.base_tarif <= " + f_tarif_max;
  }
  if (f_proprietaire != undefined) {
    where += " AND l.id_proprietaire = " + f_proprietaire;
  }
  if (f_codePostaux != undefined) {
    f_codePostaux = f_codePostaux.split(", ");
    for (let i = 0; i < f_codePostaux.length; i++) {
      if (i == 0) {
        where += " AND (a.code_postal = '" + f_codePostaux[i] + "'";
      } else {
        where += " OR a.code_postal = '" + f_codePostaux[i] + "'";
      }
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
        if (t_note != "t_init") {
          listeLogements = tri.note(listeLogements, t_note);
        }

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

function php_genererListeCoupsCoeur() {
  let where = "";

  $.ajax({
    url: "ajax/index.ajax.php",
    type: "POST",
    data: { action: "genererListeLogement", where: where },
    dataType: "json",
    success: function (reponse) {
      if (reponse.reponse) {
        let listeLogements = reponse.reponse;
        let logement;

        let divCoups = document.getElementById("les__coups");
        divCoups.innerHTML = "";

        if (listeLogements.length > 0) {
          /* construction categorie "Nos coups de coeur" */
          listeLogements = tri.note(listeLogements, "t_desc");

          let nb_card = 0;
          for (let i = 0; i < listeLogements.length; i++) {
            logement = listeLogements[i];

            if (logement.note >= 3.5) {
              divCoups.appendChild(genererCard.card_coups(nb_card, logement));
              nb_card++;
            }
          }
        } else {
          let divVide = document.createElement("div");
          let pVide = document.createElement("p");
          pVide.innerHTML =
            "Aucun logement n'est sélectionné dans la catégorie coups de cœur.";
          divVide.appendChild(pVide);
          divCoups.appendChild(divVide);
        }
      }
    },
  });
}

$(document).ready(function () {
  /* Appel de les fonctions au chargement de la page */
  php_genererAutocompletCommune();
  php_genererAutocompletProprietaire();
  php_genererListeLogement();
  php_genererListeCoupsCoeur();

  /* Appel des fonctions php_genererListeLogement et php_genererListeCoupsCoeur au clic sur le bouton Recherche */
  $("#executeRecherche").click(function () {
    php_genererListeLogement();
  });

  /* Appel de la fonction php_genererListeLogement au clic sur la tri */
  document.getElementById("tri_note").onclick = function () {
    let mode = document.getElementById("tri-note-value");
    if (mode.textContent === "t_init") {
      mode.textContent = "t_desc";
    } else if (mode.textContent === "t_desc") {
      mode.textContent = "t_asc";
    } else if (mode.textContent === "t_asc") {
      mode.textContent = "t_init";
    }
    php_genererListeLogement();
  };
});

/* Gestion buton découvrir plus / Voir moins des logements */
let btn_decouvrir = document.getElementById("decouvrir_plus");
let btn_decouvrir_moins = document.getElementById("decouvrir_moins");
let nos_logements = document.getElementById("nos_logements");

btn_decouvrir.addEventListener("click", function () {
  var elements_caches = document.querySelectorAll(".card__container_cacher");
  elements_caches.forEach(function (element) {
    element.style.display = "flex";
  });
  btn_decouvrir.style.display = "none";
  btn_decouvrir_moins.style.display = "block";
});

btn_decouvrir_moins.addEventListener("click", function () {
  var elements_caches = document.querySelectorAll(".card__container_cacher");
  elements_caches.forEach(function (element) {
    element.style.display = "none";
  });
  btn_decouvrir.style.display = "block";
  btn_decouvrir_moins.style.display = "none";
  nos_logements.scrollIntoView({ behavior: "smooth" });
});

/* Gestion buton découvrir plus / Voir moins des coups de coeurs */
let btn_decouvrir_coeur = document.getElementById("decouvrir_plus_coup_coeur");
let btn_decouvrir_coeur_moins = document.getElementById(
  "decouvrir_plus_coup_coeur_moins"
);
let nos_coups_coeur = document.getElementById("nos_coups_coeur");

btn_decouvrir_coeur.addEventListener("click", function () {
  var elements_caches = document.querySelectorAll(
    ".coups-coeur__container_cacher"
  );
  elements_caches.forEach(function (element) {
    element.style.display = "flex";
  });
  btn_decouvrir_coeur.style.display = "none";
  btn_decouvrir_coeur_moins.style.display = "block";
});

btn_decouvrir_coeur_moins.addEventListener("click", function () {
  var elements_caches = document.querySelectorAll(
    ".coups-coeur__container_cacher"
  );
  elements_caches.forEach(function (element) {
    element.style.display = "none";
  });
  btn_decouvrir_coeur.style.display = "block";
  btn_decouvrir_coeur_moins.style.display = "none";
  nos_coups_coeur.scrollIntoView({ behavior: "smooth" });
});

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
        monthNames: [
          "Janvier",
          "Février",
          "Mars",
          "Avril",
          "Mai",
          "Juin",
          "Juillet",
          "Août",
          "Septembre",
          "Octobre",
          "Novembre",
          "Décembre",
        ],
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
