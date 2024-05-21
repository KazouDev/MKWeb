<?php
    include "header.php";
    require_once "../utils.php";

    $id_utilisateur = $_GET["id"];

    $query_utilisateur = "select nom, prenom, ville, pays, civilite, photo_profile, email, telephone from sae._utilisateur
    inner join sae._adresse on sae._adresse.id = sae._utilisateur.id_adresse
    where sae._utilisateur.id = $id_utilisateur;";
    $rep_utilisateur = request($query_utilisateur, true);
    
    $prenom = $rep_utilisateur['prenom'];
    $nom = $rep_utilisateur['nom'];
    $ville = $rep_utilisateur['ville'];
    $pays = $rep_utilisateur['pays'];
    $email = $rep_utilisateur['email'];
    $telephone = $rep_utilisateur['telephone'];

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
    <link rel="stylesheet" href="css/compte.css">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <main class="main__container">
            <div class="detail_compte__conteneur">
                <img src="$src_photo" alt="photo de profil de l'utilisateur">
                <h3><?= $prenom . " " . $nom ?></h1>
                <p><?= $ville . ", " . $pays ?></p>
                <p><?= $genre ?></p>
                <p><?= $email ?></p>
                <p><?= $telephone ?></p>
            </div>
        </main>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>
