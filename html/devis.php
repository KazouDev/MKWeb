<?php
require "../utils.php";
session_start();
$id_client = client_connected_or_redirect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Récupérer toutes les variables
    $id_logement = $_POST["id_logement"];
    $date_debut = $_POST["dateDebut"];
    $date_fin = $_POST["dateFin"];
    $nb_occupant = $_POST["nombre_personnesDevis"];
    $taxe_sejour = $_POST["taxe"];
    $taxe_commission = $_POST["frais"];
    $prix_ht = $_POST["prix_ht"];
    $prix_ttc = $_POST["prix_ttc"];
    $prix_total = intval($prix_ttc) + intval($taxe_commission) + intval($taxe_sejour);
    $date_devis = date('Y-m-d');
    $nb_nuit = $_POST["nb_nuit"];



    // Requête pour vérifier les chevauchements dans la table _devis
    $sql_devis = 'SELECT * FROM sae._devis d';
    $sql_devis .= ' WHERE d.id_logement = ' . intval($id_logement);
    $sql_devis .= " AND ((d.date_debut >= '$date_debut' AND d.date_debut < '$date_fin') OR (d.date_fin > '$date_debut' AND d.date_fin <= '$date_fin'))";

    // Requête pour vérifier les chevauchements dans la table _reservation
    $sql_reservation = 'SELECT * FROM sae._reservation r';
    $sql_reservation .= ' WHERE r.id_logement = ' . intval($id_logement);
    $sql_reservation .= " AND ((r.date_debut >= '$date_debut' AND r.date_debut < '$date_fin') OR (r.date_fin > '$date_debut' AND r.date_fin <= '$date_fin'))";


    $devis = request($sql_devis, false);
    $reservation = request($sql_reservation, false);

    //Si pas de réservation ni de devis on créer le devis
    if(count($devis)==0 && count($reservation)==0){
        $table = 'sae._devis';
        $columns = [
            'id_logement',
            'id_client',
            'date_devis',
            'date_debut',
            'date_fin',
            'nb_occupant',
            'taxe_sejour',
            'taxe_commission',
            'prix_ht',
            'prix_ttc',
            'prix_total'
        ];
        $values = [
            intval($id_logement),
            intval($id_client),
            $date_devis,
            $date_debut,
            $date_fin,
            floatval($nb_occupant),
            floatval($taxe_sejour),
            floatval($taxe_commission),
            floatval($prix_ht),
            floatval($prix_ttc),
            floatval($prix_total)
        ];

        $insert_devis = insert($table, $columns, $values);
        $_SESSION["id_devis_en_cours"] = $insert_devis;
        $begin = new DateTime($date_debut);
        $end = new DateTime($date_fin);
        $end = $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval, $end);

        foreach ($daterange as $date) {
            $d = $date->format('Y-m-d');
            $sql = "INSERT INTO sae._calendrier (date, id_logement, statut, prix) VALUES ('$d', $id_logement, 'D', 0.0)
                ON CONFLICT (date, id_logement) DO UPDATE SET statut = 'D'";

            request($sql);
        }
    } else{
        if (isset($_SESSION["id_devis_en_cours"])){
            $insert_devis = intval($_SESSION["id_devis_en_cours"]);
        } else {
            header('Location: index.php');
            die;
        }
        //Message d'erreur car date indispo surement
    }
}

$sql = "SELECT d.*, u.photo_profile 
        FROM sae._devis d
        INNER JOIN sae._logement l ON l.id = d.id_logement
        INNER JOIN sae._utilisateur u ON u.id = l.id_proprietaire
        WHERE d.id = '$insert_devis'";
$reservation = request($sql, true);
// Vérification que la reservation existe puis qu'elle est bien associé au client connecté
if (!$reservation) {
    // // Redirection vers la page des réservations
    // header('Location: mes_reserv.php');
    // die();
}
// On recupère les données à afficher
else {
    /**
     * Fonction qui permet de modifier un numéro de mois en abréviation
     * 
     * param $date
     * resultat : String
     */
    function mois($date = null)
    {

        // Définir le tableau associatif des mois
        $arrayMonth = [
            1 => "jan", 2 => "fév", 3 => "mars",
            4 => "avr", 5 => "mai", 6 => "juin",
            7 => "juill", 8 => "août", 9 => "sept",
            10 => "oct", 11 => "nov", 12 => "dec"
        ];
        if (array_key_exists($date, $arrayMonth)) {
            $mois = $arrayMonth[$date];
        } else {
            return false;
        }
        return $mois;
    }

    $date1 = date_parse($reservation["date_debut"]);
    $date2 = date_parse($reservation["date_fin"]);
    $moisEnLettreDebut = mois($date1['month']);
    $moisEnLettreFin = mois($date2['month']);
    // Formation de la chaine du titre de la réservation 
    if ($moisEnLettreDebut != false && $moisEnLettreFin != false) {

        $dateReservation = $date1['day'] . " " . $moisEnLettreDebut . ". " . $date1['year'] . " - " . $date2['day'] . " " . $moisEnLettreFin . ". " . $date2['year'];
    }
    //  Récupération des valeurs du logement et propriétaire

    $idLogement = $reservation["id_logement"];
    $sql = "SELECT * from sae._logement where id=$idLogement";
    $logement = request($sql, true);
    $sql = "SELECT * from sae._utilisateur where id=$logement[id_proprietaire]";
    $proprio  = request($sql, true);
    $sql = "SELECT * from sae._adresse where id=$logement[id_adresse]";
    $adresse = request($sql, true);

    $sql = "SELECT * from sae._reservation_prix_par_nuit WHERE id_reservation=$reservation[id]";

    // Calcul et formatage des différents prix de la réservation

    $prixParNuit = request($sql, true);

    $prixHTTNuit = number_format(round($reservation["prix_ht"] / $prixParNuit["nb_nuit"], 2), 2, ",", "");

    $prixTTCnuit = number_format(round($reservation["prix_ttc"] / $prixParNuit["nb_nuit"], 2), 2, ",", "");

    $reservationPrixTTC = number_format($reservation["prix_ttc"], 2, ",", "");

    $taxeSejour = number_format($reservation["taxe_sejour"], 2, ",", "");

    $total =  number_format($reservation["prix_total"], 2, ",", "");

    $comission = number_format($reservation["taxe_commission"], 2, ",", "");

    // Coordonnées de la réservation pour la carte
    $latitude = $adresse["latitude"];
    $longitude = $adresse["longitude"];

    $bis = $adresse["rep"] ?? "";
    $adresseRue = $adresse["numero"] . " " . $bis . " " . $adresse["nom_voie"] . ", " . $adresse["code_postal"] . ", " . $adresse["commune"];

    $sql = "SELECT * from sae._image where id_logement=$logement[id] and principale=true";

    $images = request($sql, true);

    // Langues maîtrisées par le propriétaire

    $sql = "SELECT *
        FROM sae._langue_proprietaire
        INNER JOIN sae._langue ON sae._langue_proprietaire.id_langue = sae._langue.id
        WHERE sae._langue_proprietaire.id_proprietaire = $proprio[id]";

    $languesProprio = request($sql, false);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/detailsreserv.css">

    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <title>Devis</title>
</head>

<body>
    <div class="wrapper">
        <?php require_once "header.php" ?>
        <main class="main__container">
            <div class="detail-reservation__conteneur">
                <!--Haut de page des détails de la reservation selectionnée -->
                <div class="detail-reservation__entete">
                    <div>
                        <h1 class="entete__titre">Devis</h1> <!-- Récupérer la date -->
                    </div>
                    <div>
                        <div class="entete__textinfo1">
                            <p>Fait le</p>
                            <span class="couleur">16/04/2024</span>
                        </div>
                    </div>
                </div>
                <!-- Contenu principal des informations de la réservation -->
                <div class="detail-reservation__contenu">
                    <div class="detail-reservation__section1">
                        <img src="<?= htmlspecialchars("img" . $images['src']) ?>">
                        <div class="section1__article">
                            <div class="article__title">
                                <p class="gras"><?= $logement["titre"] ?></p>
                                <p class="gras"><?= $dateReservation ?></p>
                            </div>
                            <p><span class="gras">Hôte: </span><?= $proprio['prenom'] ?></p>
                            <p>
                        </div>

                    </div>
                    <div class="separation">
                    </div>
                    <div class="detail-reservation__section3">
                        <h3 class="section3__titre">Informations importantes</h3>
                        <div class="section3__informationoccupation">
                            <div class="informationoccupation__detail">
                                <div>
                                    <p class="gras">Arrivée</p>
                                    <p><?= (new DateTime($reservation["date_debut"]))->format('d/m/Y') ?>
                                    <p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Départ</p>
                                    <p><?= (new DateTime($reservation["date_fin"]))->format('d/m/Y') ?></p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Nombre de nuits</p>
                                    <p><?= $prixParNuit["nb_nuit"] ?></p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Occupant</p>
                                    <p><?= $reservation["nb_occupant"] ?></p>
                                </div>
                            </div>
                            <div class="informationoccupation__prix">
                                <p class=""><?= $prixTTCnuit ?>€ TTC par nuit </p>
                            </div>
                        </div>
                    </div>
                    <div class="separation">
                    </div>
                    <div class="detail-reservation__section4">
                        <h3 class="gras">Tarifs</h3>
                        <div class="partiemontant">
                            <div class="partiemontant__sanssoustitre">
                                <p>Location HT par nuit</p>
                                <p class="texteGras"><?= $prixHTTNuit ?> €</p>
                            </div>
                            <div class="partiemontant__soustitre">
                                <div>
                                    <p>Location TTC par nuit </p>
                                    <p class="texteGras"><?= $prixTTCnuit ?> €</p>
                                </div>
                                <p>TVA 10%</p>
                            </div>
                            <div class="partiemontant__soustitre">
                                <div>
                                    <p>Location TTC</p>
                                    <p class="texteGras"><?= $reservationPrixTTC ?> €</p>
                                </div>
                                <p>Prix par nuit TTC × <?= $prixParNuit["nb_nuit"] ?> nuits</p>
                            </div>
                            <div class="partiemontant__soustitre">
                                <div>
                                    <p>Frais supplémentaires</p>

                                </div>
                                <p>Services supplémentaires d'hébergement</p>
                            </div>
                            <div class="partiemontant__sanssoustitre">
                                <p>Taxe de séjour (2,88 € × nuits)</p>
                                <p class="texteGras"><?= $taxeSejour ?> €</p>
                            </div>
                            <div class="partiemontant__sanssoustitre">
                                <p>1% de la commission de la plateforme</p>
                                <p class="texteGras"><?= $comission ?> €</p>
                            </div>
                            <div class="montantFinal">
                                <p>Montant Final TTC</p>
                                <p class="texteGras"><?= $total ?> €</p>
                            </div>

                        </div>
                    </div>
                    <div class="separation">
                    </div>
                    <div class="detail-reservation__section5">
                        <h3 class="texteGras">Informations supplémentaires</h3>
                        <p>Veuillez noter que ce montant total n'inclut pas les suppléments (par exemple, les lits d'appoint).</p>
                        <br>
                        <p>En cas de non-présentation ou d'annulation, une partie du montant est retenu si le délai de prévenance fixé par le vendeur dans les <a href="" class="couleur">conditions d’annulation</a> n’est pas respecté (le montant retenu peut être l’intégralité de la somme prépayée). Le montant non retenu est reversé immédiatement.</p>
                    </div>
                </div>
                <div class="info_fin_page">
                </div>
            </div>
                <div class="button_accept_devis">
                            <a href="detail_logement.php?id_devis_refus=<?php echo $insert_devis ?>&id=<?php echo $id_logement ?>">
                                <input type="button" id="declineButton" value="Refuser">
                            </a>
                            <!-- Corriger le passage de nb nuit en param si le temps-->
                            <a href="detail_reservation.php?id_devis=<?php echo $insert_devis; ?>&nb_nuit=<?php echo $nb_nuit ?>">
                                <input type="submit" name="acceptButton" id="acceptButton" value="Accepter">
                            </a>
                </div>
                <div class="telecharger">
                    <img src="img/downloads.webp" alt="Download" id="downloadImage">
                    <p>
                        La version imprimable de votre confirmation contient toutes les informations importantes de votre réservation. Elle peut être utilisée lors de votre arrivée dans le logement. <br><br> 
                        Pour la télécharger, <span class="couleur" id="downloadLink">cliquez ici.</span>
                    </p>
                </div>
        </main>
        <?php require_once "footer.php" ?>
    </div>
    <div id="pdf-content" hidden>
        <div class="container" id="container">
            <div class="invoice" id="invoice">
                <div class="row">
                    <div class="col-7">
                        <div class="header__logo">                            
                            <a href="./index.php" class="header__name">ALHaiZ Breizh</a>
                        </div>
                    </div>
                    <div class="col-5">
                        <h1 class="document-type display-4">FACTURE</h1>
                        <p class="text-right"><strong>Référence facture :</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-7">
                        <p class="addressMySam">
                            <strong>MYSAM</strong><br />
                            8 avenue de la Martelle<br />
                            81150 Terssac
                        </p>
                    </div>
                    <div class="col-5">
                        <br /><br /><br />
                        <p class="addressDriver">
                            <strong>Société VTC</strong><br />
                            Réf. Client <em>Référence client</em><br />
                            Prénom NOM<br />
                            adresse<br />
                            code postal VILLE
                        </p>
                    </div>
                </div>
                <br />
                <br />
                <br />
                <h6>Frais de services MYSAM du date au date</h6>
                <br />
                <br />
                <br />
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>TVA</th>
                            <th class="text-right">Total HT</th>
                            <th class="text-right">Total TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Frais de service MySam à 5% pour la période du date au date</td>
                            <td>20%</td>
                            <td class="text-right">0,00€</td>
                            <td class="text-right">0,00€</td>
                        </tr>
                        <tr>
                            <td>Frais de service MySam à 10% pour la période du date au date</td>
                            <td>20%</td>
                            <td class="text-right">0,00€</td>
                            <td class="text-right">0,00€</td>
                        </tr>
                        <tr>
                            <td>Pénalités d'annulation</td>
                            <td>20%</td>
                            <td class="text-right">0,00€</td>
                            <td class="text-right">0,00€</td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-8"></div>
                    <div class="col-4">
                        <table class="table table-sm text-right">
                            <tr>
                                <td><strong>Total HT</strong></td>
                                <td class="text-right">0,00€</td>
                            </tr>
                            <tr>
                                <td>TVA 20%</td>
                                <td class="text-right">0,00€</td>
                            </tr>
                            <tr>
                                <td><strong>Total TTC</strong></td>
                                <td class="text-right">0,00€</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const downloadImage = document.getElementById("downloadImage");
    const downloadLink = document.getElementById("downloadLink");
    
    function generatePDF() {
        var pdfContent = document.getElementById("pdf-content").innerHTML;
        var windowObject = window.open();

        windowObject.document.write('<html><head><title>Invoice</title>');
        windowObject.document.write('<style>');
        windowObject.document.write('body { background: #ccc; padding: 30px; font-size: 0.9em;  font-family: "Plus Jakarta San", sans-serif;}');
        windowObject.document.write('h6 { font-size: 1em; }');
        windowObject.document.write('.row { display: flex; flex-wrap: nowrap; }');
        windowObject.document.write('.col-7, .col-5, .col-8, .col-4 { position: relative; width: 100%; }');
        windowObject.document.write('.col-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }');
        windowObject.document.write('.col-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }');
        windowObject.document.write('.col-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }');
        windowObject.document.write('.col-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }');
        windowObject.document.write('.logo { width: 4cm; }');
        windowObject.document.write('.document-type { text-align: right; color: #444; }');
        windowObject.document.write('.conditions { font-size: 0.7em; color: #666; }');
        windowObject.document.write('.bottom-page { font-size: 0.7em; }');
        windowObject.document.write('.header__logo { max-height: 50px; max-width: 300px; display: flex; justify-content: flex-start; align-items: center; column-gap: 5px; }');
        windowObject.document.write('.header__logo img { max-width: 100%; height: 50px; }');
        windowObject.document.write('.header__name { color: #5669FF; text-transform: lowercase; font-size: 2em; letter-spacing: 3px; font-weight: 700; }');
        windowObject.document.write('table { width: 100%; margin-bottom: 1rem; color: #212529; border-collapse: collapse; }');
        windowObject.document.write('.table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }');
        windowObject.document.write('.table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0, 0, 0, 0.05); }');
        windowObject.document.write('.text-right { text-align: right !important; }');
        windowObject.document.write('.table-sm td, .table-sm th { padding: 0.3rem; }');
        windowObject.document.write('</style>');
        windowObject.document.write('</head><body>');
        windowObject.document.write(pdfContent);
        windowObject.document.write('</body></html>');

        windowObject.document.close();
        windowObject.focus();
        windowObject.print();
        windowObject.close();
    }

    downloadImage.addEventListener("click", generatePDF);
    downloadLink.addEventListener("click", generatePDF);
});
    </script>