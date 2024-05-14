<?php 
    require "../utils.php";

    $query = "SELECT * FROM sae._compte_client 
    INNER JOIN sae._utilisateur ON sae._compte_client.id = sae._utilisateur.id
    INNER JOIN sae._adresse ON sae._utilisateur.id_adresse = sae._adresse.id
    LIMIT 1";
    var_dump(request($query));
?>
