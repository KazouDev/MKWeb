<?php 
    require "./header.php";
    require "../utils.php";

    $query_liste_logement = "SELECT * FROM sae._logement";
    $rep_liste_logement = request($query_liste_logement);    

    $logements = []; 

    foreach ($rep_liste_logement as $cle => $logement) {
        $query_image = "SELECT * FROM sae._image WHERE sae._image.id_logement = " . $logement["id"] . " AND sae._image.principale = true";
        $rep_image = request($query_image);

        $query_adresse = "SELECT * FROM sae._adresse WHERE sae._adresse.id = " . $logement["id_adresse"];
        $rep_adresse = request($query_adresse);
        $adresse = $rep_adresse[0];

        $query_avis = "SELECT avg(note) FROM sae._avis WHERE sae._avis.id_logement = " . $logement["id"];
        $rep_avis = request($query_avis);
        $avis = $rep_avis[0];
        
        # id
        # nom 
        # note 
        # ville, department
        # tarif/jour

        $logements[] = [
            "id" => $logement["id"],
            "titre" => $logement["titre"],
            "note" => $avis["avg"],
            "ville" => $adresse["ville"],
            "departement" => $adresse["departement"],
            "tarif" => $logement["base_tarif"]
        ];
    }

    print_r($logements);

?>