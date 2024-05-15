<?php 
    include "header.php";
    require "../utils.php";
    $id_logement = 2;    // /!\ mettre le bon id
    $query = "SELECT sae._logement.id AS log_id, * FROM sae._logement INNER JOIN sae._adresse ON sae._logement.id_adresse = sae._adresse.id INNER JOIN sae._type_logement ON sae._logement.id_type = sae._type_logement.id WHERE sae._logement.id ='$id_logement';"; 
    $query_note = "SELECT avg(note), count(*) from sae._avis where id_logement = $id_logement;";
    $query_amenagement = "SELECT amenagement FROM sae._amenagement_logement INNER JOIN sae._amenagement ON sae._amenagement_logement.id_amenagement = sae._amenagement.id  WHERE sae._amenagement_logement.id_logement = $id_logement;";
    $query_hote = "select prenom, nom from sae._utilisateur inner join sae._logement on sae._utilisateur.id = sae._logement.id_proprietaire where sae._logement.id = $id_logement;";
    $query_langue = "select langue from sae._utilisateur 
    inner join sae._langue_proprietaire on sae._utilisateur.id = sae._langue_proprietaire.id_proprietaire 
    inner join sae._langue on sae._langue_proprietaire.id_langue = sae._langue.id
    inner join sae._logement on sae._logement.id_proprietaire = sae._utilisateur.id
    where sae._logement.id =$id_logement;";
    $rep = request($query)[0];
    $rep_note = request($query_note)[0];
    $rep_amenagement = request($query_amenagement);
    $rep_hote = request($query_hote)[0];
    $rep_langue = request($query_langue);

    
    echo "ID: " . $rep['log_id'] . "<br>";
    echo "Nom du logement : " . $rep['titre'] . "<br>";
    echo "Note : " . $rep_note['avg'] . "<br>";
    echo "Localisation : " . $rep['ville'] . "<br>";
    echo "Département : " . $rep['departement'] . "<br>";
    echo "Accroche : " . $rep['accroche'] . "<br>";
    echo "Type : " . $rep['type'] . "<br>";
    echo "Surface : " . $rep['surface'] . "<br>";
    echo "Nombre de voyageur : " . $rep['nb_max_personne'] . "<br>";
    echo "Chambres : " . $rep['nb_chambre'] . "<br>";
    echo "Lits simples : " . $rep['nb_lit_simple'] . "<br>";
    echo "Lits doubles : " . $rep['nb_lit_double'] . "<br>";
    foreach($rep_amenagement as $amenagement){
        echo "Aménagements : " . $amenagement['amenagement'] . "<br>";
    }
    echo "Note : " . $rep_note['avg'] . "<br>";
    echo "Nombre de commentaire : " . $rep_note['count'] . "<br>";
    echo "Description : " . $rep['description'] . "<br>";
    echo "Nom de l'hôte : " . $rep_hote['nom'] . "<br>";
    echo "Prénom de l'hôte : " . $rep_hote['prenom'] . "<br>";
    echo "Langues parlées par proprio : " . $rep['surface'] . "<br>";
    echo "Langues : ";
    $liste_langue = "";
    foreach($rep_langue as $cle => $langue){
        
        if ($cle > 0) {
            $liste_langue = $liste_langue . ", ";
        }
        $liste_langue = $liste_langue . $langue['langue'];
    }
    echo $liste_langue . "<br>";
    echo "Moyens de paiement acceptés : carte bancaire et Paypal";
    echo "Choses à proximité : " . $rep['surface'] . "<br>";
    echo "Avis client : " . $rep['surface'] . "<br>";
?>