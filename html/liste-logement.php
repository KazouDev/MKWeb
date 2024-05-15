<?php 
    require "./header.php";
    require "../utils.php";
    
    $f_departement = ""; 
    $f_commune = "";
    $f_base_tarif_min = "";
    $f_base_tarif_max = "";
    $f_proprietaire = "";

    $t_tarif_base = "";



    $f_note = "";
    $f_date_arrive = "";
    $f_date_depart = "";
    
    $t_note = "";
    



    # Construct WHERE
    $where = "";
    $f_base_tarif_min != "" ? $where = $where . " AND sae._logement.base_tarif >= " . $f_base_tarif_min : $where;
    $f_base_tarif_max != "" ? $where = $where . " AND sae._logement.base_tarif <= " . $f_base_tarif_max : $where;
    $f_proprietaire   != "" ? $where = $where . " AND sae._logement.id_proprietaire = " . $f_proprietaire : $where;
    $f_departement    != "" ? $where = $where . " AND sae._adresse.departement = " . $f_departement : $where;
    $f_commune        != "" ? $where = $where . " AND sae._adresse.ville = " . $f_commune : $where;
    
    # Construct ORDER BY
    $orderBy = ""; 
    $t_tarif_base != "" ? $orderBy = $orderBy . " sae._logement.base_tarif " . $t_tarif_base : $orderBy; 
    $orderBy != "" ? $orderBy = " ORDER BY" . $orderBy : $orderBy;

    # Recuperation des donnees
    $query_liste_logement = "SELECT sae._logement.id AS id_logement,
                                    sae._adresse.id AS id_adresse, 
                                    sae._logement.titre, 
                                    sae._logement.base_tarif, 
                                    sae._adresse.departement, 
                                    sae._adresse.ville
                            FROM sae._logement INNER JOIN sae._adresse 
                                ON sae._logement.id_adresse = sae._adresse.id
                            WHERE sae._logement.en_ligne = true" . $where . $orderBy;
    $rep_liste_logement = request($query_liste_logement);

    # Recuperation Image de couverture
    # $query_image = "SELECT * FROM sae._image WHERE sae._image.id_logement = " . $logement["id"] . " AND sae._image.principale = true";
    # $rep_image = request($query_image);

    $liste_donnee = [];

    foreach ($rep_liste_logement as $donnee) {
        # Construction filtre note
        $groupBy = "";
        $having = "";
        $f_note != "" ? $groupBy = $groupBy . " sae._avis.id_logement" AND $having = $having . " avg(sae._avis.note) >= " . $f_note : $groupBy AND $having;  
        $groupBy != "" ? $groupBy = " GROUP BY" . $groupBy : $groupBy;
        $having != "" ? $having = " HAVING" . $having : $having;

        # Recuperation Note global
        $query_avis = "SELECT avg(sae._avis.note)::numeric(10,2) AS note FROM sae._avis WHERE sae._avis.id_logement = " . $donnee["id_logement"] . $groupBy . $having;
        $rep_avis = request($query_avis);
        empty($rep_avis) ? $note = null : $note = $rep_avis[0]["note"];
        
        # Construction filtres dates
        $where = "";
        if ($f_date_arrive != "" AND $f_date_arrive != "") {
            $dateArrive = new DateTime($f_date_arrive);
            $dateDepart = new DateTime($f_date_depart);
            $dateDepart->modify('+1 day');

            $interval = new DateInterval('P1D'); // Interval d'un jour
            $dates = new DatePeriod($dateArrive, $interval, $dateDepart);

            foreach ($dates as $d) {
                if ($where == "") {
                    $where = $where . " AND (sae._calendrier.date = '" . $d->format('Y-m-d') . "'"; 
                }
                else {
                    $where = $where. " OR sae._calendrier.date = '" . $d->format('Y-m-d') . "'"; 
                }
            }

            if ($where != "") {
                $where = $where . ")";
            }

            # Recuperation des logements non disponible dans l'interval
            $query_calendrier = "SELECT * FROM sae._calendrier WHERE sae._calendrier.id_logement = " . $donnee["id_logement"] . $where;
            $rep_calendier = request($query_calendrier);
        }

        # Filtre des logements en fonction de la note et/ou de la periode
        if (($f_note == "" OR !empty($rep_avis))
            AND ($f_date_arrive == "" OR empty($rep_calendier))
            AND ($f_date_depart == "" OR empty($rep_calendier))) {
            $liste_donnee[$donnee["id_logement"]] = [
                "id_logement" => $donnee["id_logement"],
                "titre" => $donnee["titre"],
                "note" => $note,
                "ville" => $donnee["ville"],
                "departement" => $donnee["departement"],
                "tarif" => $donnee["base_tarif"]
            ];    
        }
        
    }

    #Tri par note
    if ($t_note != "") {
        $liste_tempo = $liste_donnee; 
        $liste_donnee = [];

        $liste_note = []; 
        foreach ($liste_tempo as $c => $v) {
            $liste_note[$c] = $v["note"];
        }

        if ($t_note == "DESC") {
            arsort($liste_note, SORT_NUMERIC); 
        }
        else if ($t_note == "ASC") {
            asort($liste_note, SORT_NUMERIC); 
        }

        foreach ($liste_note as $c => $v) {
            $liste_donnee[$c] = $liste_tempo[$c]; 
        }
    }

    
    print_r($liste_donnee); 

?>