<?php 
    session_start();
    require "../../utils.php";

    buisness_connected_or_redirect();

    if (isset($_POST)){
        
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
                                <option value="0">Maison</option>
                                <option value="1">Appartement</option>
                                <option value="2">Villa</option>
                                <option value="3">Exotique</option>
                            </select>
                        </div>
                        <div class="info_gen__input">
                            <label for="titre">Type</label>
                            <select name="type" id="titre" required>
                                <option value="" disabled selected>Type de Logement</option>
                                <option value="0">T1</option>
                                <option value="1">T2</option>
                                <option value="2">T3</option>
                                <option value="3">F1</option>
                                <option value="4">F2</option>
                                <option value="5">F3</option>
                            </select>
                        </div>
                        <div class="info_gen__input">
                            <label for="titre">Surface</label>
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
                                <textarea id="description" name="description" placeholder="Saisir descriptif" required></textarea>
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
                            <label for="nbpersonne">Pays</label>
                            <input type="text" id="pays" name="pays" value="France" disabled required>
                        </div>
                        
                        <div class="info_gen__input">
                            <label for="nbpersonne">Région</label>
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

                        <div class="info_gen__input">
                            <label for="latitude">Coordonnées GPS</label>
                            <div class="input__input">
                                <input type="text" id="latitude" name="latitude" placeholder="Latitude" required>
                                <input type="text" id="longitude" name="longitude" placeholder="Longitude" required>
                            </div>
                        </div>

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
                            <label for="comp2">Complément 3</label>
                            <input type="text" id="comp2" name="comp2" placeholder="Saisir complément">
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
                            <input type="text" id="titre" name="titre" placeholder="ex: Villa pieds dans la mer" required>
                        </div>
                        
                        <div class="info_gen__input">
                            <label for="chambre">Prix HT</label>
                            <input type="number" id="prixht" name="prixht" placeholder="Saisissez" required>
                        </div>

                        <div class="info_gen__input">
                                <label for="dureeloc">Délai minimum de location</label>
                                <input type="number" id="dureeloc" name="dureeloc" placeholder="Saisissez" required>
                            </div>

                        <div class="info_gen__input">
                            <label for="delaires">Délai minimum réservation avant l'arrivée </label>
                            <input type="number" id="delaires" name="delaires" placeholder="Saisissez" required>
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
                        <div class="input__checkbox">
                            <input type="checkbox" name="amenagements[]" value="0">
                            <label>Four</label>
                        </div>
                        <div class="input__checkbox">
                            <input type="checkbox" name="amenagements[]" value="1">
                            <label>Climatisation</label>
                        </div>
                        <div class="input__checkbox">
                            <input type="checkbox" name="amenagements[]" value="2">
                            <label>Piscine</label>
                        </div>
                        <div class="input__checkbox">
                            <input type="checkbox" name="amenagements[]" value="3">
                            <label>Beer-Pong</label>
                        </div>
                        <div class="input__checkbox">
                            <input type="checkbox" name="amenagements[]" value="3">
                            <label>Parking gratuit sur place</label>
                        </div>
                        <div class="input__checkbox">
                            <input type="checkbox" name="amenagements[]" value="3">
                            <label>Vue sur la mer</label>
                        </div>
                        <div class="input__checkbox">
                            <input type="checkbox" name="amenagements[]" value="3">
                            <label>Sèche-linge</label>
                        </div>
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
                                <option value="0">Sur place</option>
                                <option value="1">Moins de 5km</option>
                                <option value="2">Moins de 10km</option>
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

                    <button class="envoyer">
                        Enregistrer
                    </button>
                </form>
        </main>
        <?php require_once "footer.php"; ?>
    </div>
    <script src="../js/creer-logement.js"></script>
</body>
