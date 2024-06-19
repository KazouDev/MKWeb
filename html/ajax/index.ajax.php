<?php

require "../../utils.php";

function genererSelectProprietaire() {
    $query = "SELECT sae._utilisateur.id, sae._utilisateur.nom, sae._utilisateur.prenom 
        FROM sae._utilisateur INNER JOIN sae._compte_proprietaire ON sae._utilisateur.id = sae._compte_proprietaire.id;";
    $reponse = request($query);
    return $reponse;
}

function genererListeLogement($where) {
    # Recuperation des donnees des logements
    $query = "SELECT l.id AS id_logement, a.id AS id_adresse, l.titre, l.base_tarif AS tarif, a.departement, a.commune, 
            img.src AS image_src, img.alt AS image_alt
        FROM sae._logement l
        INNER JOIN sae._adresse a ON l.id_adresse = a.id
        LEFT JOIN sae._image img ON l.id = img.id_logement AND img.principale = true
        WHERE l.en_ligne = true".$where.";";
    $reponse = request($query);
    return $reponse;
}

function genererPeriodePourListeLogement($where) {
    $query = "SELECT DISTINCT sae._calendrier.id_logement 
        FROM sae._calendrier 
        WHERE sae._calendrier.statut <> ''".$where;
    $reponse = request($query);
    return $reponse;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = ""; 
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
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

    if ($action == "genererPeriodePourListeLogement") {
        $where = $_POST['where'];

        if ($where == "") { $reponse = []; }
        else { $reponse = genererPeriodePourListeLogement($where); }
        echo json_encode(['reponse' => $reponse]);
    }
}

?>