<?php 
    session_start();
    require "../../utils.php";

    buisness_connected_or_redirect();
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

            <section class="info_gen">
                <div class="top">
                    <h2>Informations générales</h2>
                </div>
                <form action="">
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
                            <input type="number" id="simple" name="simple" placeholder="Saisissez">
                            <input type="number" id="double" name="double" placeholder="Saisissez">
                        </div>
                    </div>
                </form>
            </section>
        </main>
        <?php require_once "footer.php"; ?>
    </div>
</body>
