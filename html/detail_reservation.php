<?php
    require "../utils.php";
    session_start();
    //$id_client = client_connected_or_redirect();
    $id_client= client_connected_or_redirect();
    $idreservation = $_GET["id"];
    $sql = "SELECT id_client FROM sae._reservation WHERE id=$idreservation";
    $client = request($sql,true);
    // On vérifie que la réservation est bien associée au bon utilisateur
    $sql = "SELECT * from sae._reservation where id_client=$id_client and id=$idreservation";
    $reservation = request($sql,true);
    // Vérification que la reservation existe puis qu'elle est bien associé au client connecté
    if($reservation==null){
        // Redirection vers la page des réservations
        header('Location: mes_reserv.php');
        die();
    }
    // On recupère les données à afficher
    else{
         /**
          * Fonction qui permet de modifier un numéro de mois en abréviation
          * 
          * param $date
          * resultat : String
          */
         function mois($date = null) {
	        
	        // Définir le tableau associatif des mois
            $arrayMonth = [
                1 => "jan",2 => "fév",3 => "mar",
                4 => "avr",5 => "mai",6 => "jun",
                7 => "jul",8 => "aou",9 => "sep",
                10 => "oct",11 => "nov",12 => "déc"
            ];

            
            if (array_key_exists($date, $arrayMonth)) {
                $mois = $arrayMonth[$date];
            } else {
                return false; 
            }
	        return $mois;
 
        }
        
        $date1 = date_parse($reservation["date_debut"]);
        $date2 = date_parse($reservation["date_fin"]);
        $moisEnLettreDebut = mois($date1['month']);
        $moisEnLettreFin = mois($date2['month']);
        // Formation de la chaine du titre de la réservation 
        if($moisEnLettreDebut!=false && $moisEnLettreFin!=false){
            
            $dateReservation = $date1['day']." ".$moisEnLettreDebut.". ".$date1['year']." - ".$date2['day']." ".$moisEnLettreFin.". ".$date2['year'];
        
        }
    
        $idLogement = $reservation["id_logement"];
        $sql = "SELECT * from sae._logement where id=$idLogement";
        $logement = request($sql,true);
        $sql = "SELECT * from sae._utilisateur where id=$logement[id_proprietaire]";
        $proprio  = request($sql,true);
        $sql = "SELECT * from sae._adresse where id=$logement[id_adresse]";
        $adresse = request($sql,true);
        
        $sql = "SELECT * from sae._reservation_prix_par_nuit WHERE id_reservation=$reservation[id]";

        $prixParNuit = request($sql,true);

        $prixHTTNuit = round($reservation["prix_ht"]/$prixParNuit["nb_nuit"],2);

        
        $prixTTCnuit =round( $reservation["prix_ttc"]/$prixParNuit["nb_nuit"],2);

        $taxeSejour = $reservation["taxe_sejour"];
        
        $locationTTC = $prixParNuit["nb_nuit"] * $prixTTCnuit;

        $latitude = $adresse["latitude"];
        $longitude = $adresse["longitude"];
        
        $bis = $adresse["rep"] ?? "";
        $adresseRue = $adresse["numero"]." ".$bis." ".$adresse["nom_voie"].", ".$adresse["code_postal"].", ".$adresse["commune"];
        
        $sql= "SELECT * from sae._image where id_logement=$logement[id] and principale=true";

        $images = request($sql,true);

        $sql = "SELECT *
        FROM sae._langue_proprietaire
        INNER JOIN sae._langue ON sae._langue_proprietaire.id_langue = sae._langue.id
        WHERE sae._langue_proprietaire.id_proprietaire = $proprio[id]";

        $languesProprio = request($sql,false);
        
        

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/detailsreserv.css">

    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <title>Detail réservation</title>
</head>
<body>
    <div class="wrapper">
        <?php require_once "header.php" ?>
        <main class="main__container">
            <div class="detail-reservation__conteneur">
                <!--Haut de page des détails de la reservation selectionnée -->
                <div class="detail-reservation__entete">
                    <div>
                        <h1 class="entete__titre">Détails de la réservation</h1>
                        <a href="/detail_logement.php?id=<?= $idLogement?>"><img src="img/back.webp" alt="Back"></a>
                    </div>
                    <div>
                        <div class="entete__textinfo1">
                            <p>Numéro de confirmation : </p>
                            <span class="couleur">1</span> 
                        </div>
                        <div class="entete__textinfo2">
                            <p>Numéro de réservation :</p>
                            <span class="couleur"><?=$reservation["id"]?></span>
                        </div>
                    </div>
                </div>
                <!-- Contenu principal des informations de la réservation -->
                <div class="detail-reservation__contenu">
                    <div class="detail-reservation__section1">
                        <img src="<?= htmlspecialchars("img".$images['src']) ?>">
                        <div class="section1__article">
                            <div class="article__title">
                                <p class="gras"><?=$logement["titre"]?></p>
                                <p class="gras"><?=$dateReservation?></p>
                            </div>
                            <p><span class="gras">Adresse: </span><?=$adresseRue?></p>
                            <p><span class="gras">Coordonnées GPS: </span> N 048° 55.849, E 02° 16.963</p>
                            <p><span class="gras">Hôte: </span><?=$proprio['prenom']?></p>
                            <p><span class="gras">Téléphone: </span><?=$proprio['telephone']?></p>
                            <p>
                        </div>
                        
                    </div>  
                    <div class="separation">
                    </div>
                    <div class="hote">
                            <div class="hote__info">
                                <img src="img/user.webp" alt="Hôte" id="hote__photo">
                                <div class="hote__main">
                                    <div class="hote__nom">
                                        <h3>Hôte: <span id="hote__nm"><?=$proprio['prenom']?></span></h3>
                                        <div class="hote_rate" id="hote__rate">
                                            <i class="fas fa-star fa-xs" ></i>
                                        </div>
                                        <h6 id="hote__valuernote">5</h6>
                                    </div>
                                    <div class="hote__langues">
                                        <i class="fa-solid fa-earth-americas" style="color: #222222;"></i>
                                        <ul>
                                            <!-- On parcourt les différentes langues du propriétaire -->
                                        <?php
                                        foreach($languesProprio as $langue){
                                            ?>
                                            <li><?=$langue["langue"]?></li>
                                            <?php
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="asavoir">
                                <h2>À savoir</h2>
                                <a href="">Conditions de séjour dans сe logement</a>
                                <h3>Moyens de paiement acceptés : PayPal, carte bancaire</h3>
                            </div>
                            <div class="section2__article3" id="localisation">
                                <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                            </div>
                            <script>
                                var lat = "<?php echo $latitude; ?>";
                                var lng = "<?php echo $longitude; ?>";
                                afficherCommuneSurMap(lat, lng);

                                function afficherCommuneSurMap(lat, lng) {
                                    var map = L.map('localisation').setView([lat, lng], 9); 
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '© OpenStreetMap contributors'
                                    }).addTo(map);

                                    L.marker([lat, lng]).addTo(map).bindPopup('Le logement est ici !');
                                }
                            </script>
                        </div>  
                    <div class="separation">
                    </div>
                    <div class="detail-reservation__section3">
                        <h3 class="section3__titre">Informations importantes</h3>
                        <div class="section3__informationoccupation">
                            <div class="informationoccupation__detail">
                                <div>
                                    <p class="gras">Arrivée</p>
                                    <p><?=(new DateTime($reservation["date_debut"]))->format('d/m/Y')?><p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Départ</p>
                                    <p><?=(new DateTime($reservation["date_fin"]))->format('d/m/Y')?></p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Nombre de nuits</p>
                                    <p><?=$prixParNuit["nb_nuit"]?></p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Occupant</p>
                                    <p><?=$reservation["nb_occupant"]?></p>
                                </div>
                            </div>
                            <div class="informationoccupation__prix">
                                <p class=""><?=$prixTTCnuit?>€ TTC  par nuit </p>
                            </div>
                        </div>
                    </div>
                    <div class="separation">
                    </div>
                    <div class="detail-reservation__section4">
                        <h3 class="gras">Tarifs</h3>
                        <div class="partiemontant">
                            <div class="partiemontant__sanssoustitre">
                                <p>Location HT par nuit</p>
                                <p class="texteGras"><?=$prixHTTNuit?> €</p>
                            </div>
                            <div class="partiemontant__soustitre">
                               <div>
                                    <p>Location TTC par nuit </p>
                                    <p class="texteGras"><?=$prixTTCnuit?> €</p>
                               </div>
                               <p>TVA 10%</p>
                            </div>
                            <div class="partiemontant__soustitre">
                               <div>
                                    <p>Location TTC</p>
                                    <p class="texteGras"><?=$reservation["prix_ttc"]?> €</p>
                               </div>
                               <p>Prix par nuit TTC × nombre de nuits</p>
                            </div>
                            <div class="partiemontant__soustitre">
                               <div>
                                    <p>Frais supplémentaires</p>

                               </div>
                               <p>Services supplémentaires d'hébergement</p>
                            </div>
                            <div class="partiemontant__sanssoustitre">
                                <p>Taxe de séjour  (2,88 € ×  nuits)</p>
                                <p class="texteGras"><?=$taxeSejour?> €</p>
                            </div>
                            <div class="partiemontant__sanssoustitre">
                                <p>1% de la commission de la plateforme</p>
                                <p class="texteGras"><?=$reservation["taxe_commission"]?> €</p>
                            </div>
                            <div class="montantFinal">
                                <p>Montant Final TTC</p>
                                <p class="texteGras"><?=$reservation["prix_ttc"]+$reservation["taxe_commission"]+$taxeSejour?> €</p>
                            </div>
                            
                        </div>
                    </div>
                    <div class="separation">
                    </div>
                    <div class="detail-reservation__section5">
                        <h3 class="texteGras">Informations supplémentaires</h3>
                        <p>Veuillez noter que ce montant total n'inclut pas les suppléments (par exemple, les lits d'appoint).</p>
                        <br>
                        <p>En cas de non-présentation ou d'annulation, une partie du montant est retenu si le délai de prévenance fixé par le vendeur dans les <a href="" class="couleur">conditions d’annulation</a> n’est pas respecté (le montant retenu peut être l’intégralité de la somme prépayée). Le montant non retenu est reversé immédiatement.</p>
                    </div>
                </div>
                <div class="info_fin_page">
                            
                </div>
            </div> 
            <div class="telecharger">
                <a href=""><img src="img/downloads.webp" alt="Download"></a>
                <p>La version imprimable de votre confirmation contient toutes les informations importantes de votre réservation. Elle peut être utilisée lors de de votre arrivée dans le logement. <br><br> Pour la télécharger, <a href="" class="couleur"> cliquez ici.</a></p>
            </div>  
        </main>
        
        <?php require_once "footer.php" ?>
    </div>
    
</body>
</html>