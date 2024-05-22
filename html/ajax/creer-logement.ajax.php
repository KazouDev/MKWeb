<?php 
session_start();

  if (isset($_POST["titre"])){
      $adresse = [
        "pays" => $_POST["pays"],
        "region" => $_POST["region"],
        "departement" => $_POST["departement"],
        "ville" => $_POST["commune"],
        "rue" => $_POST["num_voie"] . " " .$_POST["voie"],
        "complement_1" => empty($_POST["comp1"]) ? "NULL" : $_POST["comp1"],
        "complement_2" => empty($_POST["comp2"]) ? "NULL" : $_POST["comp2"],
        "complement_3" => empty($_POST["comp3"]) ? "NULL" : $_POST["comp3"],
        "latitude" => $_POST["latitude"],
        "longitude" => $_POST["longitude"]
    ];

    if (!buisness_connected()){
      print json_encode(["err" => "forbidden"]);
    }

    $logement = [
        "titre" => $_POST["titre"],
        "id_proprietaire" => buisness_connected_or_redirect(),
        "id_adresse" => insert("sae._adresse", array_keys($adresse), array_values($adresse)),
        "id_categorie" => $_POST["categorie"],
        "id_type" => $_POST["type"],
        "surface" => $_POST["surface"],
        "nb_chambre" => $_POST["chambre"],
        "nb_lit_simple" => $_POST["simple"],
        "nb_lit_double" => $_POST["double"],
        "accroche" => $_POST["accroche"],
        "description" => $_POST["description"],
        "nb_max_personne" => $_POST["nbpersonne"],
        "base_tarif" => $_POST["prixht"],
        "periode_preavis" => $_POST["preavis"],
        "en_ligne" => $_POST["statut"],
        "duree_min_res" => $_POST["dureeloc"],
        "delai_avant_res" => $_POST["delaires"],
    ];

    $id_logement = insert("sae._logement", array_keys($logement), array_values($logement));

    if (isset($_POST["amenagements"])){
        foreach($_POST["amenagements"] as $amenagement){
            insert("sae._amenagement_logement", ["id_logement", "id_amenagement"], [$id_logement, $amenagement], false);
        }
    }

    /*Activite*/
    if (isset($_POST["activite"])){
        foreach($_POST["activite"] as $activite){
            $activite = explode(";;" ,$activite);
            insert("sae._activite_logement", ["id_logement", "activite", "id_distance"], [$id_logement, $activite[0], $activite[1]], false);
        }
    }

    $uploads_dir = "../../images/logement/$id_logement";
    if (!is_dir($uploads_dir)){
        mkdir($uploads_dir, 0777, true);
    }

    if (isset($_FILES["images"])){
        foreach ($_FILES["images"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["images"]["tmp_name"][$key];
                $name = basename($_FILES["images"]["name"][$key]);
                move_uploaded_file($tmp_name, "$uploads_dir/$name");
            }
        }
    }
    print json_encode(["err" => false, "id" => $id_logement]);
}

print json_encode(["err" => "invalid data"]);

?>