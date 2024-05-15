<?php 
    include "header.php";
    require "../utils.php";

    $id_logement = $_GET["id"];
    echo $id_logement . "<br>";

    //$id_logement = 1;    // /!\ mettre le bon id
    $query = "SELECT sae._logement.id AS log_id, * FROM sae._logement INNER JOIN sae._adresse ON sae._logement.id_adresse = sae._adresse.id INNER JOIN sae._type_logement ON sae._logement.id_type = sae._type_logement.id WHERE sae._logement.id ='$id_logement';"; 
    $query_note = "SELECT avg(note), count(*) from sae._avis where id_logement = $id_logement;";
    $query_amenagement = "SELECT amenagement FROM sae._amenagement_logement INNER JOIN sae._amenagement ON sae._amenagement_logement.id_amenagement = sae._amenagement.id  WHERE sae._amenagement_logement.id_logement = $id_logement;";
    $query_hote = "select prenom, nom from sae._utilisateur inner join sae._logement on sae._utilisateur.id = sae._logement.id_proprietaire where sae._logement.id = $id_logement;";
    $query_langue = "select langue from sae._utilisateur 
    inner join sae._langue_proprietaire on sae._utilisateur.id = sae._langue_proprietaire.id_proprietaire 
    inner join sae._langue on sae._langue_proprietaire.id_langue = sae._langue.id
    inner join sae._logement on sae._logement.id_proprietaire = sae._utilisateur.id
    where sae._logement.id =$id_logement;";
    $query_activite = "select activite, perimetre from sae._activite_logement 
    inner join sae._logement on sae._activite_logement.id_logement = sae._logement.id  
    inner join sae._distance on sae._activite_logement.id_distance = sae._distance.id
    where sae._logement.id = $id_logement;";
    $query_avis = "select commentaire, note, prenom, ville, pays from sae._avis 
    inner join sae._utilisateur on sae._avis.id_client = sae._utilisateur.id 
    inner join sae._adresse on sae._adresse.id = sae._utilisateur.id_adresse
    where sae._avis.id_logement =$id_logement;";
    $rep_logement = request($query)[0];
    $rep_note = request($query_note)[0];
    $rep_amenagement = request($query_amenagement);
    $rep_hote = request($query_hote)[0];
    $rep_langue = request($query_langue);
    $rep_activite = request($query_activite);
    $rep_avis = request($query_avis);

    $titre_logement =  $rep_logement['titre'] ;
    $moyenne_note = $rep_note['avg'];
    $ville = $rep_logement['ville'];
    $departement = $rep_logement['departement'];
    $accroche = $rep_logement['accroche'];
    $type = $rep_logement['type'];
    $surface = $rep_logement['surface'];
    $nb_personne = $rep_logement['nb_max_personne'];
    $nb_chambre = $rep_logement['nb_chambre'];
    $nb_lit_simple =  $rep_logement['nb_lit_simple'];
    $nb_lit_double = $rep_logement['nb_lit_double'];
    $nb_commentaire = $rep_note['count'];
    $description = $rep_logement['description'];
    $nom_hote = $rep_hote['nom'];
    $prenom_hote = $rep_hote['prenom'];

    $liste_amenagement = "";
    foreach($rep_amenagement as $cle => $amenagement){
        if ($cle > 0) {
            $liste_amenagement = $liste_amenagement . ", ";
        }
        $liste_amenagement = $liste_amenagement . $amenagement['amenagement'];
    }

    $liste_langue = "";
    foreach($rep_langue as $cle => $langue){
        
        if ($cle > 0) {
            $liste_langue = $liste_langue . ", ";
        }
        $liste_langue = $liste_langue . $langue['langue'];
    }

    $liste_activite = "";
    foreach($rep_activite as $cle => $activite){
        
        if ($cle > 0) {
            $liste_activite = $liste_activite  . "<br>";
        }
        $liste_activite = $liste_activite . $activite['activite'] . " : " . $activite['perimetre'] ;
    }

    $liste_avis = "";
    foreach($rep_avis as $cle => $avis){
        
        if ($cle > 0) {
            $liste_avis = $liste_avis  . "<br>";
        }
        $liste_avis = $liste_avis . $avis['prenom'] . ", " . $avis['ville'] .', ' . $avis['pays'] .', ' .$avis['note'] .', ' . $avis['commentaire'];
    }

    echo "ID: " . $id_logement . "<br>";
    echo "Nom du logement : " . $titre_logement . "<br>";
    if (empty($moyenne_note)) {
        echo 'Aucune note n\'a été donnée pour ce logement.' . "<br>";
    } else {
        echo "Note : " . $moyenne_note . "<br>";
    }
    echo "Localisation : " . $ville . "<br>";
    echo "Département : " . $departement . "<br>";
    echo "Accroche : " . $accroche . "<br>";
    echo "Type : " . $type . "<br>";
    echo "Surface : " . $surface . "<br>";
    echo "Nombre de voyageur : " . $nb_personne . "<br>";
    echo "Chambres : " . $nb_chambre . "<br>";
    if (empty($nb_lit_simple)) {
        echo 'Il n\'y a pas  de lit simple.'. "<br>";
    } else {
        echo "Lits simples : " . $nb_lit_simple . "<br>";
    } 
    if (empty($nb_lit_double)) {
        echo 'Il n\'y a pas  de lit double.'. "<br>";
    } else {
        echo "Lits doubles : " . $nb_lit_double . "<br>";
    }
    echo "Aménagements : " . "<br>";
    if (empty($liste_amenagement)) {
        echo 'Il n\'y a aucun aménagement pour ce logement.'. "<br>";
    } else {
        echo $liste_amenagement . "<br>";
    }
    if (empty($moyenne_note)) {
        echo 'Aucune note n\'a été donnée pour ce logement.' . "<br>";
    } else {
        echo "Note : " . $moyenne_note . "<br>";
    }
    echo "Nombre de commentaire : " . $nb_commentaire . "<br>";
    echo "Description : " . $description . "<br>";
    echo "Nom de l'hôte : " . $nom_hote . "<br>";
    echo "Prénom de l'hôte : " . $prenom_hote . "<br>";
    echo "Langues : ";
    echo $liste_langue . "<br>";
    echo "Moyens de paiement acceptés : Carte bancaire et Paypal" . "<br>" ;
    echo "Activités à proximité : " . "<br>" ;
    if (empty($liste_activite)) {
        echo 'Il n\'y a pas d\'activité à proximité.'. "<br>";
    } else {
        echo $liste_activite . "<br>";
    }
    echo "Avis client : " . "<br>";
    if (empty($liste_avis)) {
        echo 'Aucun avis disponible.'. "<br>";
    } else {
        echo $liste_avis . "<br>";
    }

?>