<?php 
    session_start();
    require "../../utils.php";

    buisness_connected_or_redirect();

    if (isset($_POST)){
        var_dump($_POST);
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
            
            <form action="" method="POST">
                <section>
                    <div class="top">
                        <h2>Informations générales</h2>
                    </div>
                    <div class="field-container">
                        <div class="info_gen__input">
                            <label for="titre">Titre</label>
                            <input type="text" id="titre" name="titre" placeholder="ex: Villa pieds dans la mer">
                        </div>
                        <div class="info_gen__input">
                            <label for="categorie">Catégorie</label>
                            <select name="categorie" id="categorie" placeholder="">
                                <option value="" disabled selected>Catégorie Logement</option>
                                <option value="0">Maison</option>
                                <option value="1">Appartement</option>
                                <option value="2">Villa</option>
                                <option value="3">Exotique</option>
                            </select>
                        </div>
                        <div class="info_gen__input">
                            <label for="titre">Type</label>
                            <select name="type" id="titre">
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
                            <input type="number" id="surface" name="surface" placeholder="Surface en m²">
                        </div>
                        
                        <div class="info_gen__input">
                            <label for="chambre">Nombre de chambres</label>
                            <input type="number" id="chambre" name="chambre" placeholder="Saisissez">
                        </div>

                        <div class="info_gen__input">
                            <label for="simple">Nombre de lit</label>
                            <div class="input__input">
                                <input type="number" id="simple" name="simple" placeholder="Saisissez" min="0" max="10">
                                <input type="number" id="double" name="double" placeholder="Saisissez">
                            </div>
                        </div>

                        <div class="info_gen__input adresse">
                            <label for="chambre">Adresse du logement</label>
                            <input type="text" id="adresse" name="adresse" placeholder="Saisir adresse">
                        </div>

                        <div class="info_gen__input">
                            <label for="latitude">Coordonnées GPS</label>
                            <div class="input__input">
                                <input type="text" id="latitude" name="latitude" placeholder="Latitude">
                                <input type="text" id="longitude" name="longitude" placeholder="Longitude">
                            </div>
                        </div>

                        <div class="full-size">
                            <div class="info_gen__input">
                                <label for="accroche">Accroche</label>
                                <textarea id="description" name="description" placeholder="Saisir descriptif"></textarea>
                            </div>

                            <div class="info_gen__input">
                                <label for="description">Descriptif détaillé</label>
                                <textarea id="description" name="description" placeholder="Saisir descriptif"></textarea>
                            </div>
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
                            <input type="text" id="titre" name="titre" placeholder="ex: Villa pieds dans la mer">
                        </div>
                        
                        <div class="info_gen__input">
                            <label for="chambre">Prix HT</label>
                            <input type="number" id="prixht" name="prixht" placeholder="Saisissez">
                        </div>

                        <div class="info_gen__input">
                                <label for="dureeloc">Délai minimum de location</label>
                                <input type="number" id="dureeloc" name="dureeloc" placeholder="Saisissez">
                            </div>

                        <div class="info_gen__input">
                            <label for="delaires">Délai minimum réservation avant l'arrivée </label>
                            <input type="number" id="delaires" name="delaires" placeholder="Saisissez">
                        </div>

                        <div class="info_gen__input">
                            <label for="statut">Statut du logement</label>
                            <select name="statut" id="statut" placeholder="">
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
                        <div class="amenagmenet__list">

                        </div>

                        <div class="amenagement__input">
                            <input type="text" id="amenagement__name">
                            <select id="amenagement__distance">
                                <option value="0">Sur place</option>
                                <option value="1">Moins de 5km</option>
                                <option value="2">Moins de 10km</option>
                            </select>
                            <button type="button" class="ajouter" id="ajouter__amenagement">Ajouter</button>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="top">
                        <h2>Photos</h2>
                        <p>Ajoutez des photos à votre logement.</p>
                    </div>
                    <div class="field-container">
                    </div>
                </section>

                <button>
                    SUBMIT
                </button>
            </form>
        </main>
        <?php require_once "footer.php"; ?>
    </div>
</body>
