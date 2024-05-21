<?php

require "../utils.php";

function genererListeDepartement() {
    $query = "SELECT DISTINCT sae._adresse.departement 
        FROM sae._logement INNER JOIN sae._adresse ON sae._logement.id_adresse = sae._adresse.id;";
    $reponse = request($query);
    return $reponse;
}

function genererListeCommune($departement) {
    $query = "SELECT DISTINCT sae._adresse.commune 
        FROM sae._logement INNER JOIN sae._adresse ON sae._logement.id_adresse = sae._adresse.id
        WHERE sae._adresse.departement = '".$departement."';";
    $reponse = request($query);
    return $reponse;
}

function genererSelectProprietaire() {
    $query = "SELECT sae._utilisateur.id, sae._utilisateur.nom, sae._utilisateur.prenom 
        FROM sae._utilisateur INNER JOIN sae._compte_proprietaire ON sae._utilisateur.id = sae._compte_proprietaire.id;";
    $reponse = request($query);
    return $reponse;
}

function genererListeLogement($where) {
    # Recuperation des donnees des logements
    $query = "SELECT l.id AS id_logement, a.id AS id_adresse, l.titre, l.base_tarif as tarif, a.departement, a.commune,
            (SELECT AVG(av.note)::numeric(10,2) 
            FROM sae._avis av 
            WHERE av.id_logement = l.id) AS note
        FROM sae._logement l INNER JOIN sae._adresse a ON l.id_adresse = a.id
        WHERE l.en_ligne = true".$where.";";
    $reponse = request($query);
    return $reponse;

    # Recuperation des image de couverture d'un logement
        /* $query_image = "SELECT * 
            FROM sae._image 
            WHERE sae._image.id_logement = " . $logement["id"] . " AND sae._image.principale = true";
        $rep_image = request($query_image); */    
    # Construction filtres dates
        /*$where = "";
        if ($f_date_arrive != "" && $f_date_arrive != "") {
            $dateArrive = new DateTime($f_date_arrive);
            $dateDepart = new DateTime($f_date_depart);
            $dateDepart->modify('+1 day');

            $interval = new DateInterval('P1D'); // Interval d'un jour
            $dates = new DatePeriod($dateArrive, $interval, $dateDepart);

            foreach ($dates as $d) {
                if ($where == "") { $where = $where . " AND (sae._calendrier.date = '" . $d->format('Y-m-d') . "'"; } 
                else { $where = $where. " OR sae._calendrier.date = '" . $d->format('Y-m-d') . "'"; }
            }

            if ($where != "") { $where = $where . ")"; }

            # Recuperation des logements non disponible sur la periode
            $query_calendrier = "SELECT * 
                FROM sae._calendrier 
                WHERE sae._calendrier.id_logement = " . $donnee["id_logement"] . $where;
            $rep_calendier = request($query_calendrier);
        }*/

        # Filtre des logements en fonction de la periode
        /*if (($f_date_arrive == "" || empty($rep_calendier))
            && ($f_date_depart == "" || empty($rep_calendier))) {*/
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = ""; 
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
    }

    if ($action == "genererListeDepartement") {
        $reponse = genererListeDepartement();
        echo json_encode(['reponse' => $reponse]);
    }

    if ($action == "genererListeCommune") {
        $departement = $_POST['departement'];

        $reponse = genererListeCommune($departement);
        echo json_encode(['reponse' => $reponse]);
    }
    
    if ($action == "genererSelectProprietaire") {
        $reponse = genererSelectProprietaire();
        echo json_encode(['reponse' => $reponse]);
    }

    if ($action == "genererListeLogement") {
        $where = $_POST['where'];
        
        $reponse = genererListeLogement($where);
        echo json_encode(['reponse' => $reponse]);
    }  
}

/*password_hash(PASSWORD_BCRYPT) {}*/

?>