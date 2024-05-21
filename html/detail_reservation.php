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
                        <h1 class="entete__titre">Détail de la reservation</h1>
                        <img src="img/back.webp" alt="">
                    </div>
                    <div>
                        <div class="entete__textinfo1">
                            <p>Numéro de confirmation : </p>
                            <span class="couleur"><?=$test1?></span>
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
                    <div class="detail-reservation__section2">
                        <div class="section2__article1">
                            <img src="img/log1.webp">
                            <div class="article_textcontent">
                                <div class="textcontent__1">
                                    <p class="gras">Hôte: <?=$proprio['prenom']?></p>
                                    <div class="note">
                                        <div class="star">
                                            <i class="fas fa-star fa-size jaune"></i>
                                        </div>
                                        <div class="star">
                                            <i class="fas fa-star fa-size jaune"></i>
                                        </div>
                                        <div class="star">
                                            <i class="fas fa-star fa-size jaune"></i>
                                        </div>
                                        <div class="star">
                                            <i class="fas fa-star fa-size jaune"></i>
                                        </div>
                                        <div class="star">
                                            <i class="fas fa-star fa-size"></i>
                                        </div>
                                        <p>4.8</p>
                                    </div>
                                </div>
                                <div class="textcontent__2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="22" viewBox="0 0 23 22" fill="none">
                                        <path d="M18.75 0.095003C19.7166 0.0940669 20.6463 0.466426 21.345 1.13439C22.0437 1.80235 22.4575 2.71432 22.5 3.68V14.345C22.5009 15.3116 22.1286 16.2413 21.4606 16.94C20.7927 17.6387 19.8807 18.0525 18.915 18.095H14.37L11.25 21.905L8.13 18.095H3.75C2.81265 18.0949 1.90932 17.7437 1.218 17.1107C0.526674 16.4777 0.0974891 15.6087 0.0150017 14.675L1.76082e-06 14.51V14.345V3.845C-0.000934364 2.87838 0.371425 1.94873 1.03939 1.25003C1.70735 0.55132 2.61931 0.137534 3.585 0.095003H18.75ZM18.75 1.595H3.75C3.17593 1.59397 2.62318 1.8124 2.2049 2.20559C1.78662 2.59877 1.53445 3.13697 1.5 3.71V14.345C1.49897 14.9191 1.7174 15.4718 2.11059 15.8901C2.50377 16.3084 3.04197 16.5606 3.615 16.595H8.85L11.25 19.535L13.65 16.595H18.75C19.3241 16.596 19.8768 16.3776 20.2951 15.9844C20.7134 15.5912 20.9656 15.053 21 14.48V3.845C21.001 3.27094 20.7826 2.71818 20.3894 2.2999C19.9962 1.88162 19.458 1.62945 18.885 1.595H18.75ZM11.25 3.095C12.844 3.09898 14.3712 3.73548 15.4962 4.8647C16.6212 5.99393 17.252 7.52352 17.25 9.1175C17.2448 10.6755 16.6338 12.1703 15.5462 13.2859C14.4587 14.4014 12.9798 15.0502 11.4225 15.095H11.235C9.677 15.0918 8.18133 14.4827 7.06437 13.3965C5.9474 12.3104 5.29674 10.8323 5.25 9.275V9.0875C5.25199 7.4975 5.88501 5.97331 7.01001 4.84971C8.13501 3.72611 9.66 3.095 11.25 3.095ZM12.51 9.845H9.9825C10.065 10.9325 10.305 11.915 10.575 12.605L10.6425 12.77L10.74 12.995C10.9125 13.3325 11.0775 13.55 11.205 13.595H11.25C11.4975 13.595 11.8875 12.89 12.1725 11.84L12.255 11.51C12.375 11.0075 12.4725 10.445 12.51 9.845ZM15.69 9.845H14.0175C13.9425 11.045 13.7025 12.185 13.3275 13.085C13.9161 12.779 14.4297 12.3467 14.8317 11.819C15.2336 11.2913 15.5139 10.6812 15.6525 10.0325L15.69 9.845ZM8.4825 9.845H6.81C6.92572 10.5302 7.19856 11.1793 7.60714 11.7415C8.01572 12.3036 8.54894 12.7635 9.165 13.085C8.79 12.185 8.55 11.0525 8.4825 9.845ZM9.1725 5.105L9.075 5.1575C8.482 5.48419 7.97035 5.94048 7.57819 6.49237C7.18602 7.04427 6.92344 7.67754 6.81 8.345H8.4825C8.5575 7.1375 8.7975 6.005 9.165 5.105H9.1725ZM11.25 4.595H11.2125C11.01 4.655 10.7325 5.12 10.485 5.8325L10.3875 6.1325C10.1736 6.85336 10.0378 7.59512 9.9825 8.345H12.51C12.3675 6.35 11.685 4.7075 11.2875 4.595H11.25ZM13.335 5.1125L13.35 5.15C13.71 6.0425 13.95 7.16 14.025 8.345H15.6825C15.572 7.69202 15.3192 7.07133 14.942 6.52696C14.5649 5.98259 14.0725 5.52786 13.5 5.195L13.335 5.105V5.1125Z" fill="#222222"/>
                                    </svg>
                                    <?php
                                        foreach($languesProprio as $langue){
                                            ?>
                                            <p><?=$langue["langue"]?></p>
                                            <?php
                                        }
                                        ?>
                                </div>
                            </div>
                        </div>
                        <div class="section2__article2">
                            <p class="gras">A savoir</p>
                            <p class="couleur">Conditions de séjour dans ce logement</p>
                            <p> <span class="gras">Moyens de paiement acceptés:</span> Paypal, carte bancaire</p>
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
                        <h3 class="gras">Tarif</h3>
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
                               <p>Services supplémentaires d'hébergement%</p>
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
                        <p>En cas de non-présentation ou d'annulation, une partie du montant est retenu si le délai de prévenance fixé par le vendeur dans les <span class="couleur">conditions d’annulation</span> n’est pas respecté (le montant retenu peut être l’intégralité de la somme prépayée). Le montant non retenu est reversé immédiatement.</p>
                    </div>
                </div>
                <div class="info_fin_page">
                            
                </div>
            </div>   
        </main>
        <?php require_once "footer.php" ?>
    </div>
    
</body>
</html>