<?php 
    include "./header.php";
    require_once "../../utils.php";
   
    

    $id_utilisateur =  buisness_connected_or_redirect();

    $query_utilisateur = "select nom, prenom, pseudo, commune, pays, region, departement,
    numero, nom_voie, civilite, photo_profile, email, telephone, date_naissance, mot_de_passe, iban, bic, complement_1, complement_2, complement_3
    from sae._utilisateur
    inner join sae._adresse on sae._adresse.id = sae._utilisateur.id_adresse
    inner join sae._compte_proprietaire on sae._compte_proprietaire.id = sae._utilisateur.id
    where sae._utilisateur.id = $id_utilisateur;";
    $rep_utilisateur = request($query_utilisateur, true);
    
    //$id = $rep_utilisateur['id'];
    $prenom = $rep_utilisateur['prenom'];
    $nom = $rep_utilisateur['nom'];
    $ville = $rep_utilisateur['commune'];
    $region = $rep_utilisateur['region'];
    $departement = $rep_utilisateur['departement'];
    $numero = $rep_utilisateur['numero'];
    $voie = $rep_utilisateur['nom_voie'];
    $pays = $rep_utilisateur['pays'];
    $email = $rep_utilisateur['email'];
    $telephone = $rep_utilisateur['telephone'];
    $mdp = $rep_utilisateur['mot_de_passe'];
    $civilite = $rep_utilisateur['civilite'];
    $date_naissance = $rep_utilisateur['date_naissance'];
    $pseudo = $rep_utilisateur['pseudo'];
    $iban = $rep_utilisateur['iban'];
    $bic = $rep_utilisateur['bic'];
    $photo = $rep_utilisateur['photo_profile'];
    $complement1 = $rep_utilisateur['complement_1'];
    $complement2 = $rep_utilisateur['complement_2'];
    $complement3 = $rep_utilisateur['complement_3'];





    if ($rep_utilisateur['civilite'] == "Mr"){
        $genre = "Homme";
    } else if ($rep_utilisateur['civilite'] == "Mme"){
        $genre = "Femme";
    } else {
        $genre = "Autre";
    }
    $src_photo = $rep_utilisateur['photo_profile'];
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/mon_compte.css">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <main class="main__container">
            <div class="detail_mon_compte__conteneur">
                <div class="header_info_compte">
                    <h2>Mon Compte</h2>
                    <div class ="identifiant_client" ><h3 id="identifiant_client">Identifiant client : </h3><h3><?= " " . $id_utilisateur ?></h3></div>
                </div>
              
                <div class="compte_form">
                    <div class="info_perso_conteneur">
                        <h3>Informations personnelles</h3>
                        <form method="POST" action="">
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__prenom">Prénom</label>
                                    <input type="text" name="prenom" id="compte__prenom" value="<?= $prenom ?>" readonly>
                                </div>
                                <div class="compte__input">
                                    <label for="compte__nom">Nom</label>
                                    <input type="text" name="nom" id="compte__nom" value="<?= $nom ?>" placeholder="Votre nom" readonly>
                                </div>
                                <div class="compte__input">
                                    <label for="compte__pseudo">Pseudo</label>
                                    <input type="text" name="pseudo" id="compte__pseudo" value="<?= $pseudo ?>" placeholder="Votre pseudo" readonly>
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="genre">Choisissez votre genre :</label>
                                    <select id="genre" name="genre" disabled>
                                        <option value="Homme" <?php if ($civilite == "Mr") echo 'selected'; ?>>Homme</option>
                                        <option value="Femme" <?php if ($civilite == "Mme") echo 'selected'; ?>>Femme</option>
                                        <option value="Autre" <?php if ($civilite == "Autre") echo 'selected'; ?>>Autre</option>
                                    </select>
                                </div>
                                <div class="compte__input">
                                    <label for="compte__date_naissance">Date de naissance</label>
                                    <input type="date" name="date_naissance" id="compte__date_naissance" value ="<?=$date_naissance?>" readonly>
                                </div>
                                <div class="compte__input">
                                    <label for="compte__telephone">Téléphone</label>
                                    <input type="tel" name="telephone" id="compte__telephone" value="<?= $telephone ?>" placeholder="Votre téléphone" readonly>
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__email">Adresse e-mail</label>
                                    <input type="email" name="email" id="compte__email" value="<?= $email ?>" placeholder="Votre e-mail" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                    

                
                    <div class= "adresse_conteneur">
                        <h3>Adresse</h3>
                        <form method="POST" action="">
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__prenom">Pays</label>
                                    <input type="text" name="pays" id="compte__pays" value="<?= $pays ?>" placeholder="Votre pays" readonly>
                                </div>
                                <div class="compte__input">
                                    <label for="compte__region">Région</label>
                                    <input type="text" name="region" id="compte__region" value="<?= $region ?>" placeholder="Votre région" readonly>
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__departement">Département</label>
                                    <input type="text" name="departement" id="compte__departement" value="<?= $departement ?>" placeholder="Votre département" readonly>
                                </div>
                                <div class="compte__input">
                                    <label for="compte__ville">Ville</label>
                                    <input type="text" name="ville" id="compte__ville" value="<?= $ville ?>" placeholder="Votre ville" readonly>
                                </div>
                            </div>
                            <div class="compte__input">
                                <label for="compte__rue">Rue</label>
                                <input type="text" name="rue" id="compte__rue" value="<?= $numero . " " . $voie ?>" placeholder="Votre rue" readonly>
                            </div>
                            <div class="compte__input">
                                <label for="compte__complement">Complément d'adresse</label>
                                <input type="text" name="complement" id="compte__complement1"  value= "<?= $complement1?>" placeholder="Complément" readonly>
                            </div>
                            <div class="compte__input">
                                <label for="compte__complement">Complément d'adresse</label>
                                <input type="text" name="complement" id="compte__complement2" value= "<?= $complement2?>" placeholder="Complément" readonly>
                            </div>
                            <div class="compte__input">
                                <label for="compte__complement">Complément d'adresse</label>
                                <input type="text" name="complement" id="compte__complement3" value= "<?= $complement3?>" placeholder="Complément" readonly>
                            </div>
                        </form>
                    </div>
                    <div class = "ensemble_flex">
                        <div class= "photo_conteneur">
                            <h3>Votre photo de profil</h3>
                            <img src="<?= "../".$photo ?>" alt="photo de profil de l'utilisateur">
                            <!-- <p>source : <?= $src_photo ?></p> -->
    <!--                         <label for="photo_profile">Votre photo de profil</label>
                            <input type="file" id="photo_profile" name="photo_profile" accept="image/png, image/jpeg" /> -->
                        </div>
                        <div class="sous_ensemble_flex">
                            <div class= "mdp_conteneur">  
                                <h3>Mot de passe</h3>
                                <div class="compte__input">
                                    <label for="compte__mdp">Mot de passe :</label>
                                    <input type="password" id="compte__mdp" name="mdp" value="<?= $mdp ?>" placeholder="mdp" readonly>
                                    <label for="showPassword">
                                        <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()"> Afficher le mot de passe
                                    </label>
                                </div>
                            </div>
                            <div class="coord_bancaire_conteneur">
                                <h3>Coordonnées bancaires</h3>
                                <form method="POST" action="">
                                    <div class="compte__input">
                                        <label for="compte__iban">IBAN :</label>
                                        <input type="text" id="compte__iban" name="iban" value="<?= $iban ?>" placeholder="IBAN" readonly>
                                        <label for="compte__bic">BIC :</label>
                                        <input type="text" id="compte__bic" name="bic" value="<?= $bic ?>" placeholder="BIC" readonly>
                                    </div>
                                </form>
                            </div>
                        </div>                        
                    </div>                    
                </div>
            </div>
        </main>
        <?php include "./footer.php"; ?>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("compte__mdp");
            var checkbox = document.getElementById("showPassword");

            if (checkbox.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>
