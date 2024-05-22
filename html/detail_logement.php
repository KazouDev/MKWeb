<?php 
    require_once "../utils.php";
    session_start();

    $id_logement = $_GET["id"];

    $query = "SELECT sae._logement.id AS log_id, * FROM sae._logement 
    INNER JOIN sae._adresse ON sae._logement.id_adresse = sae._adresse.id 
    INNER JOIN sae._type_logement ON sae._logement.id_type = sae._type_logement.id 
    INNER JOIN sae._categorie_logement ON sae._logement.id_categorie = sae._categorie_logement.id
    WHERE sae._logement.id ='$id_logement';"; 

    $rep_logement = request($query, true);
    if (!$rep_logement || !isset($rep_logement)){
        header('Location: index.php');
        die;
    }

    $query_note = "SELECT avg(note), count(*) from sae._avis where id_logement = $id_logement;";
    $query_amenagement = "SELECT amenagement FROM sae._amenagement_logement INNER JOIN sae._amenagement ON sae._amenagement_logement.id_amenagement = sae._amenagement.id  WHERE sae._amenagement_logement.id_logement = $id_logement;";
    $query_hote = "select prenom, nom from sae._utilisateur inner join sae._logement on sae._utilisateur.id = sae._logement.id_proprietaire where sae._logement.id = $id_logement;";
    $query_langue = "select langue from sae._utilisateur 
    inner join sae._langue_proprietaire on sae._utilisateur.id = sae._langue_proprietaire.id_proprietaire 
    inner join sae._langue on sae._langue_proprietaire.id_langue = sae._langue.id
    inner join sae._logement on sae._logement.id_proprietaire = sae._utilisateur.id
    where sae._logement.id =$id_logement;";
    $query_activite = "select activite, perimetre from sae._activite_logement 
    inner join sae._logement on sae._activite_logement.id_logement = sae._logement.id  
    inner join sae._distance on sae._activite_logement.id_distance = sae._distance.id
    where sae._logement.id = $id_logement;";
    $query_avis = "select commentaire, note, prenom, ville, pays from sae._avis 
    inner join sae._utilisateur on sae._avis.id_client = sae._utilisateur.id 
    inner join sae._adresse on sae._adresse.id = sae._utilisateur.id_adresse
    where sae._avis.id_logement =$id_logement;";
    $query_note_hote = "SELECT AVG(sae._avis.note) 
    FROM sae._avis 
    INNER JOIN sae._logement ON sae._avis.id_logement = sae._logement.id  
    WHERE sae._logement.id_proprietaire = (
        SELECT sae._logement.id_proprietaire 
        FROM sae._logement 
        WHERE sae._logement.id = $id_logement
    );";
    $rep_note = request($query_note)[0];
    $rep_amenagement = request($query_amenagement);
    $rep_hote = request($query_hote)[0];
    $rep_langue = request($query_langue);
    $rep_activite = request($query_activite);
    $rep_avis = request($query_avis);
    $rep_note_hote = request($query_note_hote)[0];

    $titre_logement =  $rep_logement['titre'] ;
    $moyenne_note = $rep_note['avg'];
    if (isset($moyenne_note)) {
        $moyenne_note = round($moyenne_note, 1);
    }
    

    $ville = $rep_logement['ville'];
    $departement = $rep_logement['departement'];
    $accroche = $rep_logement['accroche'];
    $categorie = $rep_logement['categorie'];
    $type = $rep_logement['type'];
    $surface = $rep_logement['surface'];
    $nb_personne = $rep_logement['nb_max_personne'];
    $nb_chambre = $rep_logement['nb_chambre'];
    $nb_lit_simple =  $rep_logement['nb_lit_simple'];
    $nb_lit_double = $rep_logement['nb_lit_double'];
    $nb_commentaire = $rep_note['count'];
    $description = $rep_logement['description'];
    $nom_hote = $rep_hote['nom'];
    $prenom_hote = $rep_hote['prenom'];
    $note_hote = $rep_note_hote['avg'];
    if (isset($note_hote)) {
        $note_hote = round($note_hote, 1);
    }

    $liste_amenagement = [];
    foreach($rep_amenagement as $cle => $amenagements){
        foreach($amenagements as $cle => $amenagement){
            $liste_amenagement[] = $amenagement;
        }
    };
    

    $liste_langue = [];
    foreach($rep_langue as $cle => $langues){
        foreach($langues as $cle => $langue){
            $liste_langue[] = $langue;
        }
    }

    $liste_activite = [];
    foreach($rep_activite as $cle => $activite){
        $liste_activite[$activite['activite']] = $activite['perimetre'] ;
    }

    $liste_avis = "";
    foreach($rep_avis as $cle => $avis){
        
        if ($cle > 0) {
            $liste_avis = $liste_avis  . "<br>";
        }
        $liste_avis = $liste_avis . $avis['prenom'] . ", " . $avis['ville'] .', ' . $avis['pays'] .', ' .$avis['note'] .', ' . $avis['commentaire'];
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/logement.css">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>
    <div class="wrapper">
        <?php     include "header.php";?>
        <main class="main">
            <div class="main__container logement">
                <div class="logement__top">
                    <div class="logement__nom">
                        <div class="nom">
                            <h1 id="logement__nom"><?php echo  $titre_logement?></h1>
                            <div class="stars" id="logement__rate">
                                <i class="fas fa-star fa-lg" id="1star"></i>
                                <i class="fas fa-star fa-lg" id="2star"></i>
                                <i class="fas fa-star fa-lg" id="3star"></i>
                                <i class="fas fa-star fa-lg" id="4star"></i>
                                <i class="fas fa-star fa-lg" id="5star"></i>
                            </div>
                            <h6 id="logement__rate__valuernote"><?php echo  $moyenne_note?></h6>
                            <a class="retour" href="" id="logement__retour"><img src="img/back.webp" alt="Retour"></a>
                        </div>
                        <div class="partager">
                            <img src="img/share.webp" alt="Partager">
                            <a href="">Partager</a>
                        </div>
                    </div>
                    <div class="logement__adr">
                        <h2 id="logement__adresse"><?php echo  $ville . ", " . $departement?></h2>
                        <a href="#logement__verifier">Réserver</a>
                    </div>
                </div>
                <div class="logement__photos">
                    <div class="photo__grille" id="logement__photo__grille">
                        <img src="img/log1.webp" alt="Logement">
                        <img src="img/log6.webp" alt="Logement">
                        <img src="img/log3.webp" alt="Logement">
                        <img src="img/log4.webp" alt="Logement">
                        <img src="img/log5.webp" alt="Logement">
                    </div>
                </div>
                <div class="logement-container">
                    <div class="logement__details">
                        <div class="details__top">
                            <div class="details__nom"><h2 id="log__nom"><?php echo  $titre_logement?></h2> <h2 id="log__details"><?php echo  $accroche?></h2></div>
                            <div class="details__features" id="features">
                                <div class="feature"><?= $categorie?></div>
                                <div class="feature"><?php echo  $type?></div>
                                <div class="feature"><?php echo  $surface?> m²</div>
                                <?php if ($nb_personne == 1) { ?>
                                    <div class="feature">1 voyageur</div>
                                <?php } else if ($nb_personne > 1) { ?>
                                    <div class="feature"><?php echo  $nb_personne?> voyageurs</div>
                                <?php } ?>
                                <div class="feature"><?php echo  $nb_chambre?> chambres</div>
                                <?php if ($nb_personne == 1) { ?>
                                    <div class="feature">1 voyageur</div>
                                <?php } else if ($nb_personne > 1) { ?>
                                    <div class="feature"><?php echo  $nb_personne?> voyageurs</div>
                                <?php } ?>
                                <?php if ((!empty($nb_lit_simple)) && ($nb_lit_simple > 1)) { ?>
                                    <div class="feature"><?php echo  $nb_lit_simple?> lits simples</div>
                                <?php } else if ((!empty($nb_lit_simple)) && ($nb_lit_simple == 1)) { ?>
                                    <div class="feature">1 lit simple</div>
                                <?php } ?>
                                <?php if ((!empty($nb_lit_double)) && ($nb_lit_double > 1)) { ?>
                                    <div class="feature"><?php echo  $nb_lit_double?> lits simples</div>
                                <?php } else if ((!empty($nb_lit_double)) && ($nb_lit_double == 1)) { ?>
                                    <div class="feature">1 lit double</div>
                                <?php } ?>
                                
                                
                            </div>
                            <h3>Ce logement vous propose</h3>
                            <div class="logement__proposNote">
                                <?php if (empty($liste_amenagement)) { ?> 
                                    <div class="proposition">Ce logement ne propose aucun aménagement</div>
                                <?php } else { ?>
                                    <div class="logement__propose">
                                        <ul>
                                            <?php foreach($liste_amenagement as $a) { ?>
                                                <li class="proposition"><?php echo $a ?></li>
                                            <?php } ?>
                                        </ul>
                                        <a href="">Conditions d’annulation</a>
                                    </div>
                                <?php }?>  
                                <div class="logement__note">
                                    <h2 id="note"><?php echo  $moyenne_note?></h2>
                                    <div class="details__stars" id="logement__rate__details">
                                        <i class="fas fa-star fa-lg" id="1star_details"></i>
                                        <i class="fas fa-star fa-lg" id="2star_details"></i>
                                        <i class="fas fa-star fa-lg" id="3star_details"></i>
                                        <i class="fas fa-star fa-lg" id="4star_details"></i>
                                        <i class="fas fa-star fa-lg" id="5star_details"></i>
                                    </div>
                                    <?php 
                                        if($nb_commentaire == 1){?>
                                            <a href=""><span id="nb_comment"></span>1 Commentaire</a>
                                        <?php } else if ($nb_commentaire > 1) { ?>
                                            <a href=""><span id="nb_comment"><?php echo  $nb_commentaire . " "?></span>Commentaires</a>
                                        <?php } else { ?>
                                            <p><span id="nb_comment">Aucun commentaire</span><p>
                                        <?php }
                                    ?>
                                    

                                </div>

                            </div>
                        </div>
                        <div class="apropos">
                            <h3>À propos de ce logement</h3>
                            <p id="logement__descipt">
                                <?php echo  $description?>
                            </p>
                            <button id="decouvrir">Découvrir plus</button>
                        </div>
                        <div class="hote">
                            <div class="hote__info">
                                <img src="img/compte/<?php echo $source?>" alt="Hôte" id="hote__photo">
                                <div class="hote__main">
                                    <div class="hote__nom">
                                        <h3>Hôte: <span id="hote__nm"><?php echo  $prenom_hote?></span></h3>
                                        <div class="hote_rate" id="hote__rate">
                                            <i class="fas fa-star fa-xs" ></i>
                                            <i class="fas fa-star fa-xs" ></i>
                                            <i class="fas fa-star fa-xs" ></i>
                                            <i class="fas fa-star fa-xs" ></i>
                                            <i class="fas fa-star fa-xs" ></i>
                                        </div>
                                        <h6 id="hote__valuernote"><?= $note_hote?></h6>
                                    </div>
                                    <div class="hote__langues">
                                        <i class="fa-solid fa-earth-americas" style="color: #222222;"></i>
                                        <ul>
                                            <?php foreach($liste_langue as $l) { ?>
                                                <li class="proposition"><?php echo $l ?></li>
                                            <?php } ?> 
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="asavoir">
                                <h2>À savoir</h2>
                                <a href="">Conditions de séjour dans сe logement</a>
                                <h3>Moyens de paiement acceptés : PayPal, Carte bancaire</h3>
                            </div>
                        </div>
                        <div class="environs">
                            
                            <div class="environs__map" id="environs__map">
                                <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                            </div> 
                            <script>
                                var ville = "<?php echo $ville; ?>";
                                var opencageUrl = "https://api.opencagedata.com/geocode/v1/json?q=" + encodeURIComponent(ville) + "&key=90a3f846aa9e490d927a787facf78c7e";
                                console.log(ville);
                                fetch(opencageUrl)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.results.length > 0) {
                                            afficherCommuneSurMap(data.results[0].geometry.lat, data.results[0].geometry.lng);
                                            console.log(data);
                                        } else {
                                            console.error("La ville à afficher n'est pas valide.");
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Erreur lors de la requête de géocodage:", error);
                                    });

                                function afficherCommuneSurMap(lat, lng) {
                                    var map = L.map('environs__map').setView([lat, lng], 9); 
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '© OpenStreetMap contributors'
                                    }).addTo(map);

                                    L.marker([lat, lng]).addTo(map).bindPopup('Le logement est ici !');
                                }
                            </script>
                            <div class="environs__details">
                                <h3>Environs de l'établissement</h3>
                                <?php if (empty($liste_activite)) { ?> 
                                    <div class="environs__ligne">Il n'y a rien à proxmité.</div>
                                <?php } else { ?>
                                    <?php foreach($liste_activite as $act => $distance) { ?>
                                        <div class="environs__ligne">
                                            <p class="environ"><?php echo $act?></p>
                                            <p class="dest"><?php echo $distance?></p>
                                        </div>
                                    <?php } ?>
                                <?php }?>  
                                
                            </div>
                        </div>
                        <div class="avis">
                            <h3>Les avis des clients</h3>
                            <div class="lesavis">
                                <div class="avis__element">
                                    <div class="avis__top">
                                        <div class="avis__user">
                                            <img src="img/user.webp" alt="User">
                                            <div class="user__nom">
                                                <h5>Pierre</h5>
                                                <h6>Paris, France</h6>
                                            </div>
                                        </div>
                                        <div class="avis__note">
                                            <div class="avis_rate">
                                                <i class="fas fa-star fa-xs" id="avis__1star"></i>
                                                <i class="fas fa-star fa-xs" id="avis__2star"></i>
                                                <i class="fas fa-star fa-xs" id="avis__3star"></i>
                                                <i class="fas fa-star fa-xs" id="avis__4star"></i>
                                                <i class="fas fa-star fa-xs" id="avis__5star"></i>
                                            </div>
                                            <h6 >juin 2023</h6>
                                        </div>
                                    </div>
                                    <p>Nous avons passé un excellent séjour dans ce superbe appartement avec une vue magnifique, à quelques minutes de la plage et de superbes promenades côtières. Le restaurant d'à côté vaut également le détour. </p>

                                </div>
                                <div class="avis__element">
                                    <div class="avis__top">
                                        <div class="avis__user">
                                            <img src="img/user.webp" alt="User">
                                            <div class="user__nom">
                                                <h5>Mohamed</h5>
                                                <h6>Dakar, Sénégal</h6>
                                            </div>
                                        </div>
                                        <div class="avis__note">
                                            <div class="avis_rate">
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                            </div>
                                            <h6 >juin 2023</h6>
                                        </div>
                                    </div>
                                    <p>Deuxième séjour dans ce petit nid et toujours aussi ravi.Ne dit on pas jamais deux sans trois....</p>

                                </div>
                                <div class="avis__element">
                                    <div class="avis__top">
                                        <div class="avis__user">
                                            <img src="img/user.webp" alt="User">
                                            <div class="user__nom">
                                                <h5>Greta</h5>
                                                <h6>Berlin, Allemagne</h6>
                                            </div>
                                        </div>
                                        <div class="avis__note">
                                            <div class="avis_rate">
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                            </div>
                                            <h6 >juin 2023</h6>
                                        </div>
                                    </div>
                                    <p>Magnifique gîte proche de la mer et d’une propreté irréprochable!!! Cyril est un hôte très sympathique et réactif aux demandes!! À conseiller sans modération!</p>

                                </div>
                                <div class="avis__element">
                                    <div class="avis__top">
                                        <div class="avis__user">
                                            <img src="img/user.webp" alt="User">
                                            <div class="user__nom">
                                                <h5>James</h5>
                                                <h6>Angleterre, Royaume-Uni</h6>
                                            </div>
                                        </div>
                                        <div class="avis__note">
                                            <div class="avis_rate">
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                                <i class="fas fa-star fa-xs" ></i>
                                            </div>
                                            <h6 >juin 2023</h6>
                                        </div>
                                    </div>
                                    <p>Endroit magnifique avec un hôte parfait... le logement est un véritable havre de paix et les alentours sont exceptionnels, même à pieds..
                                        Deuxième séjour dans ce petit nid et toujours aussi ravi.Ne dit on pas jamais deux sans trois....</p>

                                </div>
                            </div>
                            <button id="decouvrir__avis">Découvrir plus</button>
                        </div>

                    </div>
                    <!--  Partie résa-->
                    
                    <?php

                    require_once '../utils.php';
                    $id = 1;
                    $sql = 'SELECT base_tarif FROM sae._logement';
                    $sql .= ' WHERE id = ' . $id;
                    $res = request($sql,1);
                    
                    $base_tarif = $res['base_tarif'];
                   
                    if (isset($_POST['submit_resa'])){

                        // CRÉATION RÉSERVATION

                    }

                    ?>
                
                        
                    <div class="logement__res" id="logement__reserver">
                        <div class="form__logement">
                            <h2><span  id="logement__prix"><?=$base_tarif?></span> € par  nuit</h2>
                            <h1>Indiquez les dates pour voir les tarifs</h1>
                            <form action="" method="post">
                            <div class="res__fromulaire">
                                
                                    <input type="text" name="dateDebut" hidden >
                                    <input type="text" name="dateFin" hidden>
                                    
                                    <div id="container-calendar">
                                    <div id="error_periode">Une réservation doit être supérieur à 4 jours</div>
                                        <h3>Arrivée - Départ</h3>
                                        <div class="calendar-nav">
                                            <button type="button" class="calendar-btn" id="prev">&lt;</button>
                                            <span class="calendar-month" id="month"></span>
                                            <button type="button" class="calendar-btn" id="next">&gt;</button>
                                        </div>
                                        <table id="calendar"></table>
                                    </div>
                                
                                <div class="res__voy">
                                    <label for="nb_personnesDevis">Voyageurs</label>
                                    <input type="number" id="nb_personnesDevis" placeholder="1 voyageur" name="nombre_personnesDevis" min="1" max="13" required>
                                </div>
                                

                            </div>

                            <div id="appear_calcul" style="display:none">
                                <div class="logement__calcules" >
                                    <div class="calcules__ligne">
                                        <div class="ttc__jours">
                                            <p id="prix__TTC" class="calcules__under"></p>
                                            <p class="calcules__under">€  x</p>
                                            <p id="nb_jours" class="calcules__under"></p>
                                            <p class="calcules__under">jours</p>
                                        </div>
                                        <div class="ttc_prix">
                                            <p id="prix__total"></p>
                                            <p>€  HT</p>
                                        </div>
                                    </div>
                                    <div class="calcules__ligne">
                                        <p class="calcules__under">Frais</p>
                                        <div class="frais">
                                            <p id="frais__total"> </p>
                                            <p>€</p>
                                        </div>
                                    </div>
                                    <div class="calcules__ligne">
                                        <p class="calcules__under">Taxes</p>
                                        <div class="frais">
                                            <p id="taxes__total"></p>
                                            <p>€</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="logement__total-ttc">
                                    <p>Total TTC</p>
                                    <p><span id="tot-ttc"></span>€</p>
                                </div>
                                <!--<input type="submit" id="reset" value="Annuler"> -->
                                <input type="submit" name="submit_resa" value="Réserver">
                                
                            </div>
                        </form>
                        </div>
                    </div>
            </div>
            </div>
        </main>
        <?php include_once 'footer.php'; ?>
    </div>
    
    
    <script src="js/header_user.js"></script>
    <script src="js/logement.js"></script>
</body>
</html>

