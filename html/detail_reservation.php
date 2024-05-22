<?php
    require "../utils.php";
    //$idreservation = $_GET["id"]
    //id du client = client_connected()
    $idclient  = 1;
    $idreservation = 1;
    // On vérifie que la réservation est bien associée au bon utilisateur
    $sql = "SELECT * from sae._reservation where id_client=$idclient and id=$idreservation";
    $verifAssociation = request($sql,true);
    // Vérification que la reservation existe puis qu'elle est bien associé au client connecté
    if($verifAssociation==null){
        // Redirection vers la page des réservations
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
	        
	        switch ($date) {
	            case 1:
		            $mois = "jan";
		            break;
	            case 2:
		            $mois = "fév";
		            break;
	            case 3:
		            $mois = "mar";
		            break;
	            case 4:
		            $mois = "Avr";
		            break;
	            case 5:
		            $mois = "mai";
		            break;
	            case 6:
		            $mois = "Jun";
		            break;
	            case 7:
		            $mois = "jul";
		            break;
	            case 8:
		            $mois = "aou";
		            break;
	            case 9:
		            $mois = "sep";
		            break;
	            case 10:
		            $mois = "oct";
		            break;
	            case 11:
		            $mois = "nov";
		            break;
	            case 12:
		            $mois = "déc";
		            break;
	            default:
		            return false;
	        }
	        return $mois;
 
        }
        
        $date1 = date_parse($verifAssociation["date_debut"]);
        $date2 = date_parse($verifAssociation["date_fin"]);
        $moisEnLettreDebut = mois($date1['month']);
        $moisEnLettreFin = mois($date2['month']);
        if($moisEnLettreDebut!=false && $moisEnLettreFin!=false){
            
            $dateReservation = $date1['day']." ".$moisEnLettreDebut.". ".$date1['year']." - ".$date2['day']." ".$moisEnLettreFin.". ".$date2['year'];
        
        }
    
        $idLogement = $verifAssociation["id_logement"];
        $sql = "SELECT * from sae._logement where id=$idLogement";
        $logement = request($sql,true);
        $sql = "SELECT * from sae._utilisateur where id=$logement[id_proprietaire]";
        $proprio  = request($sql,true);
        $sql = "SELECT * from sae._adresse where id=$logement[id_adresse]";
        $adresse = request($sql,true);

        $sql = "SELECT * from sae._reservation_prix_par_nuit WHERE id_reservation=$verifAssociation[id]";

        $prixParNuit = request($sql,true);

        $prixHTTNuit = round($verifAssociation["prix_ht"]/$prixParNuit["nb_nuit"],2);

        
        $prixTTCnuit =round( $verifAssociation["prix_ttc"]/$prixParNuit["nb_nuit"],2);

        $taxeSejour = $prixParNuit["nb_nuit"] * 2.88;
        
        $locationTTC = $prixParNuit["nb_nuit"] * $prixTTCnuit;

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
                        <a href=""><img src="img/back.webp" alt="Back"></a>
                    </div>
                    <div>
                        <div class="entete__textinfo1">
                            <p>Numéro de confirmation : </p>
                            <span class="couleur"><!-- <?=$test1?>--> 1 </span> 
                        </div>
                        <div class="entete__textinfo2">
                            <p>Numéro de réservation :</p>
                            <span class="couleur"><?=$verifAssociation["id"]?></span>
                        </div>
                    </div>
                </div>
                <!-- Contenu principal des informations de la réservation -->
                <div class="detail-reservation__contenu">
                    <div class="detail-reservation__section1">
                        <img src="img/log1.webp">
                        <div class="section1__article">
                            <div class="article__title">
                                <p class="gras"><?=$logement["titre"]?></p>
                                <p class="gras"><?=$dateReservation?></p>
                            </div>
                            <p><span class="gras">Adresse: </span><?=$adresse['rue'].", ".$adresse['ville']?></p>
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
                            <div class="section2__article3">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8544.925581204967!2d-2.5527066852125144!3d48.586915081517375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x480e0e90a5504f43%3A0x40ca5cd36e62fc0!2s22370%20Pl%C3%A9neuf-Val-Andr%C3%A9!5e0!3m2!1sfr!2sfr!4v1715792791097!5m2!1sfr!2sfr" width="250" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>  
                    <div class="separation">
                    </div>
                    <div class="detail-reservation__section3">
                        <h3 class="section3__titre">Informations importantes</h3>
                        <div class="section3__informationoccupation">
                            <div class="informationoccupation__detail">
                                <div>
                                    <p class="gras">Arrivée</p>
                                    <p><?=(new DateTime($verifAssociation["date_debut"]))->format('d/m/Y')?><p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Départ</p>
                                    <p><?=(new DateTime($verifAssociation["date_fin"]))->format('d/m/Y')?></p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Nombre de nuits</p>
                                    <p><?=$prixParNuit["nb_nuit"]?></p>
                                </div>
                                <div class="separation2"></div>
                                <div>
                                    <p class="gras">Occupant</p>
                                    <p><?=$verifAssociation["nb_occupant"]?></p>
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
                                    <p class="texteGras"><?=$verifAssociation["prix_ttc"]?> €</p>
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
                                <p>Taxe de séjour  (€ 2,88 ×  nuits)</p>
                                <p class="texteGras"><?=$taxeSejour?> €</p>
                            </div>
                            <div class="partiemontant__sanssoustitre">
                                <p>1% de la commission de la plateforme</p>
                                <p class="texteGras"><?=$verifAssociation["taxe_commission"]?> €</p>
                            </div>
                            <div class="montantFinal">
                                <p>Montant Final TTC</p>
                                <p class="texteGras"><?=$verifAssociation["prix_ttc"]+$verifAssociation["taxe_commission"]+$taxeSejour?> €</p>
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