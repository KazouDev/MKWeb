<?php 
    require "../utils.php";
    $query = "SELECT * FROM sae._logement WHERE id ='1' "; // mettre le bon id
    $rep = request($query);
    foreach($rep as $row){
        echo "ID: " . $row['id'] . "<br>";
        echo "Nom du logement : " . $row['titre'] . "<br>";
        echo "Localisation : " . $row['titre'] . "<br>";

    }

?>