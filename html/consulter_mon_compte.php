<?php 
    include "header.php";
    require_once "../utils.php";

    $id_utilisateur = $_GET["id"];

    $query_utilisateur = "select nom, prenom, pseudo, ville, pays, region, departement,
    rue, civilite, photo_profile, email, telephone, date_naissance, mot_de_passe 
    from sae._utilisateur
    inner join sae._adresse on sae._adresse.id = sae._utilisateur.id_adresse
    where sae._utilisateur.id = $id_utilisateur;";
    $rep_utilisateur = request($query_utilisateur, true);
    
    //$id = $rep_utilisateur['id'];
    $prenom = $rep_utilisateur['prenom'];
    $nom = $rep_utilisateur['nom'];
    $ville = $rep_utilisateur['ville'];
    $region = $rep_utilisateur['region'];
    $departement = $rep_utilisateur['departement'];
    $rue = $rep_utilisateur['rue'];
    $pays = $rep_utilisateur['pays'];
    $email = $rep_utilisateur['email'];
    $telephone = $rep_utilisateur['telephone'];
    $mdp = $rep_utilisateur['mot_de_passe'];
    $civilite = $rep_utilisateur['civilite'];
    $date_naissance = $rep_utilisateur['date_naissance'];
    $pseudo = $rep_utilisateur['pseudo'];


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
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/mon_compte.css">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <main class="main__container">
            <div class="detail_mon_compte__conteneur">
                <h1>Mon Compte<h1>
                <p>Identifiant client : <?= $id_utilisateur ?></p>
                <div class= "info_perso_conteneur">
                    <h3>Informations personnelles</h3>
                    <p>Prénom</p>
                    <p>Nom</p>
                    <p><?= $prenom . " " . $nom ?></p>
                    <p>Pseudo</p>
                    <p><?= $pseudo ?></p>
                    <p>Genre</p>
                    <p><?= $genre ?></p>
                    <p>Email</p>
                    <p><?= $email ?></p>
                    <p>Téléphone</p>
                    <p><?= $telephone ?></p> 
                    <form method="POST" action="">
                        <div class="connect__input">
                            <label for="connect__email">Adresse e-mail</label>
                            <input type="email" name="email" id="connect__email" placeholder="Veuillez saisir votre adresse e-mail">
                        </div>
                        <div class="connect__input">    
                            <label for="connect__pass">Mot de passe</label>
                            <input type="password" name="password" id="connect__pass" placeholder="Saisissez un mot de passe">
                        </div>
                    </form>

                </div>
                <div class= "adresse_conteneur">
                    <p>Ville</p>
                    <p><?= $ville?> </p>
                    <p>Pays</p>
                    <p><?= $pays ?></p>   
                </div>
                <div class= "photo_conteneur">
                    <img src="<?= $src_photo ?>" alt="photo de profil de l'utilisateur">
                    <p>source : <?= $src_photo ?></p>
                </div>
                <div class= "mdp_conteneur">  
                    <p>Mot de passe</p>  
                </div>
            </div>
        </main>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>
