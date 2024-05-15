<?php 
    require "../utils.php";

    $where = " WHERE";

    $query_liste_logement = "SELECT * FROM sae._logement INNER JOIN sae._adresse ON sae._logement.id_adresse = sae._adresse.id";
    $rep_liste_logement = request($query_liste_logement);

    foreach ($rep_liste_logement as $cle => $value) {
        print_r($value);
        echo "<br>";
    }

?>