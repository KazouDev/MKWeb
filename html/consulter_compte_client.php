<?php
    include "header.php";
    require_once "../utils.php";

    $id_utilisateur = $_GET["id"];
    echo $id_utilisateur . "<br>";

    $query_utilisateur = "select nom, prenom, ville, pays, civilite, photo_profile from sae._utilisateur
    inner join sae._adresse on sae._adresse.id = sae._utilisateur.id_adresse
    where sae._utilisateur.id = $id_utilisateur;";
    $rep_utilisateur = request($query_utilisateur, true);
    echo $rep_utilisateur;
?>
