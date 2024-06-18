<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="css/index.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <title>ALHaiZ Breizh</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="wrapper">
      <?php require_once 'header.php'; ?>
      <main class="main">
        <div class="top">
          <div class="top__container top-cont">
            <h1>Votre retraite bretonne vous attend</h1>
            <h2 class="top__nom">Trouvez votre hébergement idéal</h2>
            <div class="checkLogement">
              <h2>Vérifier la disponibilité</h2>
              <div class="checkLogement__tri">
                <div class="tri__element">
                  <label for="communeInput">Commune</label>
                  <input
                    type="text"
                    id="communeInput"
                    placeholder="Où ?"
                  />
                  <div
                    id="autocomplete-list-commune"
                    class="autocomplete-suggestions"
                  ></div>
                </div>
                <div class="tri__element">
                  <label for="daterange">Arrivée - Départ</label>
                  <input
                    type="text"
                    id="daterange"
                    name="daterange"
                    placeholder="Période ?"
                  />
                </div>
                <div class="tri__element">
                  <label for="nb_personnes">Nombre de voyageurs</label>
                  <input
                    type="number"
                    id="nb_personnes"
                    placeholder="Combien ?"
                    name="nombre_personnes"
                    min="1"
                    max="13"
                  />
                </div>
                <div class="tri__element">
                  <label for="tarif">Tarif/jour</label>
                  <div id="tarif_range">
                    <input
                      type="number"
                      placeholder="min"
                      id="tarif_min"
                      name="tarif_min"
                      min="0"
                      step="5"
                    />
                    <input
                      type="number"
                      placeholder="max"
                      id="tarif_max"
                      name="tarif_max"
                      min="0"
                      step="5"
                    />
                  </div>
                </div>
                <div class="tri__element">
                  <label for="proprietaireInput">Propriétaires</label>
                  <input
                    type="text"
                    id="proprietaireInput"
                    placeholder="Qui ?"
                  />
                  <div
                    id="autocomplete-list-proprietaire"
                    class="autocomplete-suggestions"
                  ></div>
                </div>
                <div class="tri__element">
                  <input
                    id="executeRecherche"
                    type="submit"
                    value="Rechercher"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="main__container main__logement">
          <div class="list__logements">
            <div class="titre_nos_log">
              <h3 class="list__titre" id="nos_logements">
                Nos logements
              </h3>
              
              <button id="tri_note">
                  <img src="img/sort.webp" alt="Sort" />
            </button>
            </div>
            <div class="les__logements" id="les__logements"></div>
            <button class="logement__plus" id="decouvrir_plus">
              Découvrir plus
            </button>
            <button
              class="logement__plus"
              id="decouvrir_moins"
              style="display: none"
            >
              Voir moins
            </button>
          </div>
        </div>
      </main>
      <?php require_once 'footer.php'; ?>
    </div>

    <div id="filtre-departement-code" style="display: none"></div>
    <div id="filtre-commune-codePostal" style="display: none"></div>
    <div id="filtre-propri-id" style="display: none"></div>
    <div id="filtre-date-deb" style="display: none"></div>
    <div id="filtre-date-fin" style="display: none"></div>

    <div id="tri-note-value" style="display: none">t_init</div>

    <script src="js/index.js"></script>
  </body>
</html>
