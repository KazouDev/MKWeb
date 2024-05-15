<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once "../utils.php";

$id = 1;
$sql = 'SELECT src FROM sae._image WHERE id_logement = ' . $id;
$res = request($sql);

if(!$res):
    echo 'indispo';
else:
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/footer.css">
        <link rel="stylesheet" href="css/logement.css">
        <title>Document</title>
        <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="wrapper">
        <?php require_once 'header.php';?>
            <main class="main">
                <div class="main__container logement">
                    <div class="logement__top">
                        <div class="logement__nom">
                            <div class="nom">
                                <h1 id="logement__nom">Ô gîte de la plage</h1>
                                <div class="stars" id="logement__rate">
                                    <i class="fas fa-star fa-lg" id="1star"></i>
                                    <i class="fas fa-star fa-lg" id="2star"></i>
                                    <i class="fas fa-star fa-lg" id="3star"></i>
                                    <i class="fas fa-star fa-lg" id="4star"></i>
                                    <i class="fas fa-star fa-lg" id="5star"></i>
                                </div>
                                <h6 id="logement__rate__valuernote">5</h6>
                                <a class="retour" href="" id="logement__retour"><img src="img/back.webp" alt="Retour"></a>
                            </div>
                            <div class="partager">
                                <img src="img/share.webp" alt="Partager">
                                <a href="">Partager</a>
                            </div>
                        </div>
                        <div class="logement__adr">
                            <h2 id="logement__adresse">Pléneuf-Val-André, Côtes-d'Armor</h2>
                            <a href="">Réserver</a>
                        </div>
                    </div>
                    <div class="logement__photos">
                        <div class="photo__grille" id="logement__photo__grille">
                            <?php for($i = 0; $i < 4; ++$i):?>
                                <?php 
                                $file = $res[$i]['src'] ?? '';
                                if(!empty($file)):?>
                                    <img src="<?=$file?>" alt="Logement">
                                <?php else:
                                        break;
                                    endif;?>
                            <?php endfor;?>
                        </div>
                        <img src="img/log5.webp" alt="Logement" id="main__photo">
                    </div>
                    <div class="logement-container">
                        <div class="logement__details"></div>
                        <div class="logement__reserver">
                            <div class="logement__res" id="logement__verifier">
                                <h2>Indiquez les dates pour voir les tarifs</h2>
                                <form action="">
                                    <div class="res__fromulaire">
                                        <div class="res__dates">
                                            <div class="res__arrive">
                                                <label for="reservArr">Arrivée</label>
                                                <input type="date" name="reservArr" id="reservArr" value="2024-05-30" min="2024-05-30" required>
                                            </div>
                                            <div class="res__dep">
                                                <label for="reservDep">Départ</label>
                                                <input type="date" name="reservDep" id="reservDep" value="2024-05-30" min="2024-05-30" required>
                                            </div>
                                        </div>
                                        <div class="res__voy">
                                            <label for="nb_personnes">Voyageurs</label>
                                            <input type="number" id="nb_personnes" placeholder="1 voyageur" name="nombre_personnes" min="1" max="13" required>
                                        </div>
                                    </div>
                                    <input type="submit" value="Vérifier la disponibilité">

                                
                            </div>
                            <div class="logement__res" id="logement__reserver">
                                <h2><span id="logement__prix">286</span> € par  nuit</h2>
                                
                                    <div class="res__fromulaire">
                                        <div class="res__dates">
                                            <div class="res__arrive">
                                                <label for="reservArrDevis">Arrivée</label>
                                                <input type="date" name="reservArrDevis" id="reservArrDevis" value="2024-05-30" min="2024-05-30" required>
                                            </div>
                                            <div class="res__dep">
                                                <label for="reservDepDevis">Départ</label>
                                                <input type="date" name="reservDepDevis" id="reservDepDevis" value="2024-05-30" min="2024-05-30" required>
                                            </div>
                                        </div>
                                        <div class="res__voy">
                                            <label for="nb_personnesDevis">Voyageurs</label>
                                            <input type="number" id="nb_personnesDevis" placeholder="1 voyageur" name="nombre_personnesDevis" min="1" max="13" required>
                                        </div>
                                    </div>
                                    <div class="logement__calcules">
                                        <div class="calcules__ligne">
                                            <div class="ttc__jours">
                                                <p id="prix__TTC" class="calcules__under">286</p>
                                                <p class="calcules__under">€  x</p>
                                                <p id="nb_jours" class="calcules__under">5</p>
                                                <p class="calcules__under">jours</p>
                                            </div>
                                            <div class="ttc_prix">
                                                <p id="prix__total">1430</p>
                                                <p>€  TTC</p>
                                            </div>
                                        </div>
                                        <div class="calcules__ligne">
                                            <p class="calcules__under">Frais</p>
                                            <div class="frais">
                                                <p id="frais__total">14,3 </p>
                                                <p>€</p>
                                            </div>
                                        </div>
                                        <div class="calcules__ligne">
                                            <p class="calcules__under">Taxes</p>
                                            <div class="frais">
                                                <p id="taxes__total">14,4 </p>
                                                <p>€</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="logement__total-ttc">
                                        <p>Total TTC</p>
                                        <p><span id="tot-ttc">1 458,7 </span>€</p>
                                    </div>
                                    <input type="submit" value="Réserver">

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div class="footer__container">
                    <nav class="footer__menu">
                        <ul class="footer__menu__list">
                            <li class="footer__menu__item">© 2024 ALHalZ Breizh</li>
                            <li class="footer__menu__item footer__menu__item-point">·</li>
                            <li class="footer__menu__item">
                                <a href="" class="footer__menu__link">Confidentialité</a>
                            </li>
                            <li class="footer__menu__item footer__menu__item-point">·</li>
                            <li class="footer__menu__item">
                                <a href="" class="footer__menu__link">Conditions générales</a>
                            </li>
                            <li class="footer__menu__item footer__menu__item-point">·</li>
                            <li class="footer__menu__item">
                                <a href="" class="footer__menu__link">Mentions légales</a>
                            </li>
                            <li class="footer__menu__item footer__menu__item-point">·</li>
                            <li class="footer__menu__item">
                                <a href="" class="footer__menu__link">Accessibilité</a>
                            </li>
                            <li class="footer__menu__item footer__menu__item-point">·</li>
                            <li class="footer__menu__item">
                                <a href="" class="footer__menu__link">Ajouter mon établissement</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </footer>
        </div>
        <script src="js/script.js"></script>
        
    </body>
    </html>

<?php
endif;