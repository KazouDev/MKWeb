<?php 
    session_start();
    require "../../utils.php";

    buisness_connected_or_redirect();

    if (isset($_POST["titre"])){
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        /*
          id SERIAL PRIMARY KEY,
  id_proprietaire INT NOT NULL,
  id_adresse INT NOT NULL,
  titre VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  accroche VARCHAR(255) NOT NULL,
  base_tarif FLOAT NOT NULL,
  surface INT NOT NULL,
  nb_max_personne INT NOT NULL,
  nb_chambre INT NOT NULL,
  nb_lit_simple INT NOT NULL,
  nb_lit_double INT NOT NULL,
  periode_preavis INT NOT NULL,
  en_ligne BOOLEAN NOT NULL,
  id_categorie INT NOT NULL,
  id_type INT NOT NULL*/
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
            "periode_preavis" => $_POST["delaires"],
            "en_ligne" => $_POST["statut"]
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

    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/creer-logement.css">
    <title>Nouveau Logement</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
</head>
<body class="page">
    <div class="wrapper">
        <?php require_once "header.php"; ?>
        <main class="main__container creer-logement">
            <div class="top">
                <div>
                    <h1 class="entete__titre">Ajouter un logement</h1>
                    <img src="../img/back.webp" alt="">
                </div>
            </div>
            
            <form id="nv-logement" action="" method="POST" enctype="multipart/form-data">
                <section>
                    <div class="top">
                        <h2>Informations générales</h2>
                    </div>
                    <div class="field-container">
                        <div class="info_gen__input">
                            <label for="titre">Titre</label>
                            <input type="text" id="titre" name="titre" placeholder="ex: Villa pieds dans la mer" required>
                        </div>
                        <div class="info_gen__input">
                            <label for="categorie">Catégorie</label>
                            <select name="categorie" id="categorie" placeholder="" required>
                                <option value="" disabled selected>Catégorie Logement</option>
                                <?php foreach(request("SELECT * FROM sae._categorie_logement") as $cat){ ?>
                                    <option value=<?= $cat["id"]?>><?=$cat["categorie"]?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="info_gen__input">
                            <label for="titre">Type</label>
                            <select name="type" id="type" required>
                                <option value="" disabled selected>Type de Logement</option>
                                <?php foreach(request("SELECT * FROM sae._type_logement") as $cat){ ?>
                                    <option value=<?= $cat["id"]?>><?=$cat["type"]?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="info_gen__input">
                            <label for="surface">Surface</label>
                            <input type="number" id="surface" name="surface" placeholder="Surface en m²" required>
                        </div>
                        
                        <div class="info_gen__input">
                            <label for="chambre">Nombre de chambres</label>
                            <input type="number" id="chambre" name="chambre" placeholder="Saisissez" required>
                        </div>

                        <div class="info_gen__input">
                            <label for="simple">Nombre de lit</label>
                            <div class="input__input">
                                <input type="number" id="simple" name="simple" placeholder="Simple" min="0" max="10" required>
                                <input type="number" id="double" name="double" placeholder="Double" required>
                            </div>
                        </div>

                        <div class="full-size">
                            <div class="info_gen__input">
                                <label for="accroche">Accroche</label>
                                <textarea id="accroche" name="accroche" placeholder="Saisir descriptif" required></textarea>
                            </div>

                            <div class="info_gen__input">
                                <label for="description">Descriptif détaillé</label>
                                <textarea id="description" name="description" placeholder="Saisir descriptif" required></textarea>
                            </div>
                        </div>
                        
                    </div>
                </section>

                <section>
                    <div class="top">
                        <h2>Adresse</h2>
                    </div>
                    <div class="field-container">
                        <div class="info_gen__input">
                            <label for="pays">Pays</label>
                            <input type="text" id="pays" name="pays" value="France" disabled required>
                        </div>
                        
                        <div class="info_gen__input">
                            <label for="region">Région</label>
                            <input type="text" id="region" name="region" value="Bretagne" disabled required>
                        </div>

                        <div class="info_gen__input">
                            <label for="departement">Département</label>
                            <select name="departement" id="departement" required>
                                <option value="" disabled selected>Selectionner</option>
                                <option value="Finistère">Finistère</option>
                                <option value="Morbihan">Morbihan</option>
                                <option value="Ille-et-Vilaine">Ille-et-Vilaine</option>
                                <option value="Côtes-d'Armor">Côtes-d'Armor</option>
                            </select>
                        </div>

                        <div class="info_gen__input">
                            <label for="commune">Commune</label>
                            <input type="text" id="commune" name="commune" placeholder="Saisissez" required>
                        </div>

                        <div class="info_gen__input">
                            <label for="">Code Postal</label>
                            <input type="number" id="cp" name="cp" placeholder="29400" required>
                        </div>

                        <input type="hidden" id="latitude" name="latitude" placeholder="Latitude" required>
                        <input type="hidden" id="longitude" name="longitude" placeholder="Longitude" required>

                        <div class="info_gen__input adresse">
                            <label for="voie">Voie</label>
                            <input type="text" id="voie" name="voie" placeholder="Nom de voie" required>
                        </div>

                        <div class="info_gen__input">
                            <label for="num_voie">Numéro Voie</label>
                            <div class="input__input">
                                <input type="number" id="num_voie" name="num_voie" placeholder="12" required>
                            </div>
                        </div>

                        <div class="info_gen__input adresse">
                            <label for="comp1">Complément 1</label>
                            <input type="text" id="comp1" name="comp1" placeholder="Saisir complément">
                        </div>

                        <div class="info_gen__input adresse">
                            <label for="comp2">Complément 2</label>
                            <input type="text" id="comp2" name="comp2" placeholder="Saisir complément">
                        </div>

                        <div class="info_gen__input adresse">
                            <label for="comp3">Complément 3</label>
                            <input type="text" id="comp3" name="comp3" placeholder="Saisir complément">
                        </div>
                    </div>
                </section>

                <section>
                    <div class="top">
                        <h2>Informations sur la réservation</h2>
                    </div>
                    <div class="field-container">
                        <div class="info_gen__input">
                            <label for="nbpersonne">Nombre max de personne</label>
                            <input type="number" id="nbpersonne" name="nbpersonne" placeholder="6" required>
                        </div>
                        
                        <div class="info_gen__input">
                            <label for="prixht">Prix HT</label>
                            <input type="number" id="prixht" name="prixht" placeholder="123.5" required>
                        </div>

                        <div class="info_gen__input">
                                <label for="dureeloc">Durée minimum de location</label>
                                <input type="number" id="dureeloc" name="dureeloc" placeholder="Saisissez" required>
                            </div>

                        <div class="info_gen__input">
                            <label for="delaires">Délai minimum réservation avant l'arrivée </label>
                            <input type="number" id="delaires" name="delaires" placeholder="Saisissez" required>
                        </div>

                        <div class="info_gen__input">
                            <label for="delaires">Délai d'annulation </label>
                            <input type="number" id="preavis" name="preavis" placeholder="Saisissez" required>
                        </div>


                        <div class="info_gen__input">
                            <label for="statut">Statut du logement</label>
                            <select name="statut" id="statut" placeholder="" required>
                                <option value="" disabled selected>Choisir</option>
                                <option value="0">En ligne</option>
                                <option value="1">Hors ligne</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="top">
                        <h2>Aménagement(s)</h2>
                        <p>Ajoutez des aménagement présent dans votre logements.</p>
                    </div>
                    <div class="field-container check__list">
                        
                        <?php foreach(request("SELECT * FROM sae._amenagement") as $ame){ ?>
                            <div class="input__checkbox">
                            <input type="checkbox" name="amenagements[]" value=<?=$ame["id"]?>>
                            <label><?=$ame["amenagement"]?></label>
                        </div>
                        <?php } ?>
                    </div>
                </section>

                <section>
                    <div class="top">
                        <h2>Environs du logement</h2>
                        <p>Ajoutez des activités disponibles à proximité de votre logement.</p>
                    </div>
                    <div class="field-container">
                        <div id="list__amenagement">
                        </div>

                        <div class="amenagement__input">
                            <input type="text" id="name__amenagement">
                            <select id="distance__amenagement">
                            <?php foreach(request("SELECT * FROM sae._distance") as $distance){ ?>
                                <option value=<?= $distance["id"]?>><?=ucfirst($distance["perimetre"])?></option>
                            <?php } ?>
                            </select>
                            <button type="button" class="ajouter" id="ajouter__amenagement">Ajouter</button>
                        </div>
                    </div>
                </section>

                <section id="section__image">
                    <div class="top">
                        <h2>Photos</h2>
                        <p>Ajoutez des photos à votre logement.</p>
                    </div>
                    <div class="container">
                        <p>Previsualiation des images</p>
                        <div id="image-preview">
                            <p>Aucune image chargé.</p>
                        </div>
                    </div>
                    <input type="file" id="image-input" accept=".jpg,.jpeg,.png,.webp" hidden multiple>
                    <input type="button" value="Ajouter une image" onclick="document.getElementById('image-input').click();" />
                </section>

                    <button id="form__submit" class="envoyer">
                        Enregistrer
                    </button>
                </form>
        </main>
        <div class="loading__modal">
            <span class="loader"></span>
        </div>
        <?php require_once "footer.php"; ?>
    </div>
    <script src="../js/creer-logement.js"></script>
</body>
