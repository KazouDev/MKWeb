<?php
session_start();
require_once "../../utils.php";

function clean_input($data)
{
    return htmlspecialchars(trim($data));
}

function validateIBAN($iban)
{
    $iban = strtoupper(str_replace(' ', '', $iban));
    $iban_length = [
        'AL' => 28,
        'AD' => 24,
        'AT' => 20,
        'AZ' => 28,
        'BH' => 22,
        'BY' => 28,
        'BE' => 16,
        'BA' => 20,
        'BR' => 29,
        'BG' => 22,
        'CR' => 22,
        'HR' => 21,
        'CY' => 28,
        'CZ' => 24,
        'DK' => 18,
        'DO' => 28,
        'EG' => 29,
        'SV' => 28,
        'EE' => 20,
        'FO' => 18,
        'FI' => 18,
        'FR' => 27,
        'GE' => 22,
        'DE' => 22,
        'GI' => 23,
        'GR' => 27,
        'GL' => 18,
        'GT' => 28,
        'HU' => 28,
        'IS' => 26,
        'IQ' => 23,
        'IE' => 22,
        'IL' => 23,
        'IT' => 27,
        'JO' => 30,
        'KZ' => 20,
        'XK' => 20,
        'KW' => 30,
        'LV' => 21,
        'LB' => 28,
        'LI' => 21,
        'LT' => 20,
        'LU' => 20,
        'MT' => 31,
        'MR' => 27,
        'MU' => 30,
        'MD' => 24,
        'MC' => 27,
        'ME' => 22,
        'NL' => 18,
        'MK' => 19,
        'NO' => 15,
        'PK' => 24,
        'PS' => 29,
        'PL' => 28,
        'PT' => 25,
        'QA' => 29,
        'RO' => 24,
        'LC' => 32,
        'SM' => 27,
        'ST' => 25,
        'SA' => 24,
        'RS' => 22,
        'SC' => 31,
        'SK' => 24,
        'SI' => 19,
        'ES' => 24,
        'SE' => 24,
        'CH' => 21,
        'TL' => 23,
        'TN' => 24,
        'TR' => 26,
        'UA' => 29,
        'AE' => 23,
        'GB' => 22,
        'VG' => 24
    ];

    $country_code = substr($iban, 0, 2);
    if (!array_key_exists($country_code, $iban_length) || strlen($iban) != $iban_length[$country_code]) {
        return false;
    }

    $iban = substr($iban, 4) . substr($iban, 0, 4);
    $iban = preg_replace_callback('/[A-Z]/', function ($match) {
        return ord($match[0]) - 55;
    }, $iban);

    $checksum = intval($iban[0]);
    for ($i = 1, $len = strlen($iban); $i < $len; $i++) {
        $checksum = intval($checksum . $iban[$i]) % 97;
    }

    return $checksum === 1;
}

function validateBIC($bic)
{
    return preg_match('/^[A-Za-z]{4}[A-Za-z]{2}[A-Za-z0-9]{2}([A-Za-z0-9]{3})?$/', $bic);
}

function validateAccountHolder($holder)
{
    return !empty($holder) && is_string($holder);
}

$id_utilisateur = buisness_connected_or_redirect();
$query_utilisateur = "select nom, prenom, pseudo, commune, pays, region, departement,
    numero, nom_voie, civilite, photo_profile, email, telephone, date_naissance, mot_de_passe, iban, bic, titulaire, complement_1, complement_2, complement_3, _adresse.code_postal, id_adresse,sae._carte_identite.piece_id_recto, sae._carte_identite.piece_id_verso
    from sae._utilisateur
    inner join sae._adresse on sae._adresse.id = sae._utilisateur.id_adresse
    inner join sae._compte_proprietaire on sae._compte_proprietaire.id = sae._utilisateur.id
    inner join sae._carte_identite on sae._carte_identite.id_propr= sae._compte_proprietaire.id
    where sae._utilisateur.id = $id_utilisateur;";
$rep_utilisateur = request($query_utilisateur, true);
$id = $id_utilisateur;
$id_add = $rep_utilisateur['id_adresse'];

$recto_photo = $rep_utilisateur['piece_id_recto'];
$verso_photo = $rep_utilisateur['piece_id_verso'];

$recto = "../img$recto_photo";
$verso = "../img$verso_photo";

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
$code_postal = $rep_utilisateur['code_postal'];

$complement1 = $rep_utilisateur['complement_1'];
$complement2 = $rep_utilisateur['complement_2'];
$complement3 = $rep_utilisateur['complement_3'];

$bic = $rep_utilisateur['bic'];
$iban = $rep_utilisateur['iban'];
$titulaire = $rep_utilisateur['titulaire'];

$ibanInvalid = false;
$bicInvalid = false;
$accountHolderInvalid = false;

if ($rep_utilisateur['civilite'] == "Mr") {
    $genre = "Homme";
} else if ($rep_utilisateur['civilite'] == "Mme") {
    $genre = "Femme";
} else {
    $genre = "Autre";
}
$src_photo = $rep_utilisateur['photo_profile'];
$passwordVerify = false;
$passwordLengthInvalid = false;
$passwordMismatch = false;
$dateMin = date('Y') - 16 . '-01-01';
$mailInvalid = false;
$emailExists = false;
$allow = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type'])) {
        $form_type = $_POST['form_type'];
        switch ($form_type) {
            case 'infos_personnelles':
                $nom = empty($_POST['nom']) ? $rep_utilisateur['nom'] : clean_input($_POST['nom']);
                $prenom = empty($_POST['prenom']) ? $rep_utilisateur['prenom'] : clean_input($_POST['prenom']);
                $pseudo = empty($_POST['pseudo']) ? $rep_utilisateur['pseudo'] : clean_input($_POST['pseudo']);
                $civilite = empty($_POST['genre']) ? $rep_utilisateur['civilite'] : clean_input($_POST['genre']);
                $date_naissance = empty($_POST['date_naissance']) ? $rep_utilisateur['date_naissance'] : (new DateTime($_POST["date_naissance"]))->format("Y-m-d");
                $telephone = empty($_POST['telephone']) ? $rep_utilisateur['telephone'] : clean_input($_POST['telephone']);
                $email = empty($_POST['email']) ? $rep_utilisateur['email'] : clean_input($_POST['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mailInvalid = true;
                    $allow = false;
                }
                if ($email != $rep_utilisateur['email']) {
                    $query = "SELECT COUNT(*) AS count FROM sae._utilisateur WHERE email = '$email'";
                    $result = request($query, true);
                    if ($result && $result["count"] > 0) {
                        $emailExists = true;
                        $allow = false;
                    }
                }

                if ($allow) {
                    request("UPDATE sae._utilisateur SET nom = '$nom', prenom = '$prenom', pseudo = '$pseudo', civilite = '$civilite', date_naissance = '$date_naissance', telephone = '$telephone', email = '$email' WHERE id =  $id_utilisateur");
                }

                break;


            case 'adresse':
                $pays = empty($_POST['pays']) ? $rep_utilisateur['pays'] : clean_input($_POST['pays']);
                $region = empty($_POST['region']) ? $rep_utilisateur['region'] : clean_input($_POST['region']);
                $departement = empty($_POST['departement']) ? $rep_utilisateur['departement'] : clean_input($_POST['departement']);
                $ville = empty($_POST['commune']) ? $rep_utilisateur['commune'] : clean_input($_POST['commune']);
                $code_postal = empty($_POST['code_postal']) ? $rep_utilisateur['code_postal'] : clean_input($_POST['code_postal']);
                $voie = empty($_POST['rue']) ? $rep_utilisateur['nom_voie'] : clean_input($_POST['rue']);
                $numero = empty($_POST['numero']) ? $rep_utilisateur['numero'] : clean_input($_POST['numero']);
                $complement1 = empty($_POST['complement1']) ? $rep_utilisateur['complement_1'] : clean_input($_POST['complement1']);
                $complement2 = empty($_POST['complement2']) ? $rep_utilisateur['complement_2'] : clean_input($_POST['complement2']);
                $complement3 = empty($_POST['complement3']) ? $rep_utilisateur['complement_3'] : clean_input($_POST['complement3']);

                request("UPDATE sae._adresse SET pays = '$pays', region = '$region', departement = '$departement', commune = '$ville', code_postal = '$code_postal', nom_voie = '$voie', numero = '$numero', complement_1 = '$complement1', complement_2 = '$complement2', complement_3 = '$complement3' WHERE id =  $id_add");

                break;


            case 'photo':
                if (isset($_FILES["photo_profile"]["tmp_name"]) && $_FILES["photo_profile"]["tmp_name"] !== "") {
                    $extension = pathinfo($_FILES["photo_profile"]['name'], PATHINFO_EXTENSION);
                    $photo_path = "../img/compte/profile_" . $rep_utilisateur['pseudo'] . "." . $extension;
                    $photo_path_bdd = "/compte/profile_" . $rep_utilisateur['pseudo'] . "." . $extension;

                    if (move_uploaded_file($_FILES["photo_profile"]["tmp_name"], $photo_path)) {
                        $sql = "UPDATE sae._utilisateur SET photo_profile = '$photo_path_bdd' WHERE id = $id_utilisateur";
                        request($sql, false);

                        $_SESSION["business_photo"] = $photo_path_bdd;

                        header('Location: consulter_mon_compte_proprio.php');
                        exit();
                    } else {
                        break;
                    }
                } else {
                    break;
                }




            case 'motdepas':
                $current_mdp = clean_input($_POST['mdp']);
                $new_mdp = empty($_POST['new_mdp']) ? $rep_utilisateur['mot_de_passe'] : clean_input($_POST['new_mdp']);
                $new_mdp2 = empty($_POST['new_mdp2']) ? $rep_utilisateur['mot_de_passe'] : clean_input($_POST['new_mdp2']);
                if (password_verify($current_mdp, $rep_utilisateur['mot_de_passe'])) {
                    if ($new_mdp === $new_mdp2) {
                        if (strlen($new_mdp) >= 8) {
                            $new_hashed_mdp = password_hash($new_mdp, PASSWORD_DEFAULT);
                            request("UPDATE sae._utilisateur SET mot_de_passe = '$new_hashed_mdp' WHERE id = $id_utilisateur");

                        } else {
                            $passwordLengthInvalid = true;

                        }

                    } else {
                        $passwordMismatch = true;

                    }
                } else {
                    $passwordVerify = true;
                }
                break;

                case 'identite':
                    $photo_recto_uploaded = false;
                    $photo_verso_uploaded = false;
                
                    // Traiter la photo recto
                    if (isset($_FILES["photo_recto"]["tmp_name"]) && $_FILES["photo_recto"]["tmp_name"] !== "") {
                        $extension_recto = pathinfo($_FILES["photo_recto"]['name'], PATHINFO_EXTENSION);
                        $photo_path_recto = "../img/piece/$id" . "_" . $nom . "_recto" . "." . $extension_recto;
                        $photo_path_recto_bdd = "/piece/$id" . "_" . $nom . "_recto" . "." . $extension_recto;
                
                        if (move_uploaded_file($_FILES["photo_recto"]["tmp_name"], $photo_path_recto)) {
                            $sql = "UPDATE sae._carte_identite SET piece_id_recto = '$photo_path_recto_bdd' WHERE id = $id";
                            request($sql, false);
                            $photo_recto_uploaded = true;
                        }
                    }
                
                    // Traiter la photo verso
                    if (isset($_FILES["photo_verso"]["tmp_name"]) && $_FILES["photo_verso"]["tmp_name"] !== "") {
                        $extension_verso = pathinfo($_FILES["photo_verso"]['name'], PATHINFO_EXTENSION);
                        $photo_path_verso = "../img/piece/$id" . "_" . $nom . "_verso" . "." . $extension_verso;
                        $photo_path_verso_bdd = "/piece/$id" . "_" . $nom . "_verso" . "." . $extension_verso;
                
                        if (move_uploaded_file($_FILES["photo_verso"]["tmp_name"], $photo_path_verso)) {
                            $sql = "UPDATE sae._carte_identite SET piece_id_verso = '$photo_path_verso_bdd' WHERE id = $id";
                            request($sql, false);
                            $photo_verso_uploaded = true;
                        }
                    }
                
                    // Si l'une des photos a été correctement téléchargée, rediriger l'utilisateur
                    if ($photo_recto_uploaded || $photo_verso_uploaded) {
                        header('Location: consulter_mon_compte_proprio.php');
                        exit();
                    }
                    break;
                

            case 'paiment':
                $iban = empty($_POST['iban']) ? $rep_utilisateur['iban'] : clean_input($_POST['iban']);
                $bic = empty($_POST['bic']) ? $rep_utilisateur['bic'] : clean_input($_POST['bic']);
                $titulaire = empty($_POST['titulaire']) ? $rep_utilisateur['titulaire'] : clean_input($_POST['titulaire']);
                if (!validateIBAN($iban)) {
                    $ibanInvalid = true;
                    $allow = false;
                }

                if (!validateBIC($bic)) {
                    $bicInvalid = true;
                    $allow = false;
                }

                if (!validateAccountHolder($titulaire)) {
                    $accountHolderInvalid = true;
                    $allow = false;
                }
                if ($allow) {
                    $sql = "UPDATE sae._compte_proprietaire SET iban = '$iban', bic = '$bic', titulaire = '$titulaire' WHERE id = $id_utilisateur";
                    request($sql, false);
                    header('Location: consulter_mon_compte_proprio.php');
                    exit();
                } else {
                    break;
                }

        }
    }
}


?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/mon_compte.css">
    <title>Mon Compte</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="wrapper">
        <?php include "./header.php"; ?>
        <main class="main__container">
            <div class="detail_mon_compte__conteneur">
                <div class="header_info_compte">
                    <h2>Mon Compte</h2>
                    <div class="identifiant_client">
                        <h3 id="identifiant_client">Identifiant propriétaire : </h3>
                        <h3><?= " " . $id_utilisateur ?></h3>
                    </div>
                </div>

                <div class="compte_form">
                    <div class="info_perso_conteneur">
                        <h3>Informations personnelles</h3>
                        <!-- <?php if ($emailExists): ?>
                                <p class="login_invalid">Cette adresse e-mail est déjà utilisée.</p>
                            <?php endif; ?>
                            <?php if ($mailInvalid): ?>
                                <p class="login_invalid">Adresse e-mail invalide</p>
                            <?php endif; ?> -->
                        <form method="POST" action="">
                            <input type="hidden" name="form_type" value="infos_personnelles">
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__nom">Nom</label>
                                    <input type="text" name="nom" id="compte__nom" value="<?= $nom ?>"
                                        placeholder="Votre nom"
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s']/g, '');">
                                </div>
                                <div class="compte__input">
                                    <label for="compte__prenom">Prénom</label>
                                    <input type="text" name="prenom" id="compte__prenom" value="<?= $prenom ?>"
                                        placeholder="Votre prénom"
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s']/g, '');">
                                </div>

                                <div class="compte__input">
                                    <label for="compte__pseudo">Pseudo</label>
                                    <input type="text" name="pseudo" id="compte__pseudo" value="<?= $pseudo ?>"
                                        placeholder="Votre pseudo">
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="genre">Civilité</label>
                                    <select id="genre" name="genre">
                                        <option value="Mr" <?php if ($civilite == "Mr")
                                            echo 'selected'; ?>>Homme</option>
                                        <option value="Mme" <?php if ($civilite == "Mme")
                                            echo 'selected'; ?>>Femme
                                        </option>
                                    </select>
                                </div>
                                <div class="compte__input">
                                    <label for="compte__date_naissance">Date de naissance</label>
                                    <input type="date" name="date_naissance" id="compte__date_naissance"
                                        value="<?= $date_naissance ?>" max="<?php echo $dateMin; ?>">
                                </div>
                                <div class="compte__input">
                                    <label for="compte__telephone">Téléphone portable</label>
                                    <input type="text" name="telephone" id="compte__telephone" value="<?= $telephone ?>"
                                        placeholder="Votre numéro"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__email">Adresse e-mail</label>
                                    <input type="email" name="email" id="compte__email" value="<?= $email ?>"
                                        placeholder="Ex : exemple@domaine.com">
                                </div>
                                <input class="sauvegarde" type="submit" value="Enregistrer">
                            </div>
                        </form>
                    </div>



                    <div class="adresse_conteneur">
                        <h3>Adresse de facturation</h3>

                        <form method="POST" action="">
                            <input type="hidden" name="form_type" value="adresse">
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__prenom">Pays</label>
                                    <input type="text" name="pays" id="compte__pays" value="<?= $pays ?>"
                                        placeholder="Votre pays"
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s']/g, '');">
                                </div>
                                <div class="compte__input">
                                    <label for="compte__region">Région</label>
                                    <input type="text" name="region" id="compte__region" value="<?= $region ?>"
                                        placeholder="Votre région"
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s']/g, '');">
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__departement">Département</label>
                                    <input type="text" name="departement" id="compte__departement"
                                        value="<?= $departement ?>" placeholder="Votre département"
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s']/g, '');">
                                </div>
                                <div class="compte__input">
                                    <label for="compte__ville">Ville</label>
                                    <input type="text" name="commune" id="compte__ville" value="<?= $ville ?>"
                                        placeholder="Votre ville"
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s']/g, '');">
                                </div>
                                <div class="compte__input">
                                    <label for="compte__ville">Code postal</label>
                                    <input type="text" name="code_postal" id="compte__code_postal"
                                        value="<?= $code_postal ?>" placeholder="Votre code postal"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__rue">Nom de la rue</label>
                                    <input type="text" name="rue" id="compte__rue" value="<?= $voie ?>"
                                        placeholder="Votre rue"
                                        oninput="this.value = this.value.replace(/[^a-zA-Z\s']/g, '');">
                                </div>
                                <div class="compte__input">
                                    <label for="compte__rue">Numéro de rue</label>
                                    <input type="number" name="numero" id="compte__rue_numero" value="<?= $numero ?>"
                                        placeholder="Numéro de votre rue"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__complement">Complément d'adresse</label>
                                    <input type="text" name="complement1" id="compte__complement1"
                                        value="<?= $complement1 ?>" placeholder="Complément">
                                </div>
                                <div class="compte__input">
                                    <label for="compte__complement">Complément d'adresse</label>
                                    <input type="text" name="complement2" id="compte__complement2"
                                        value="<?= $complement2 ?>" placeholder="Complément">
                                </div>
                            </div>
                            <div class="ligne">
                                <div class="compte__input">
                                    <label for="compte__complement">Complément d'adresse</label>
                                    <input type="tel" name="complement3" id="compte__complement3"
                                        value="<?= $complement3 ?>" placeholder="Complément">
                                </div>
                                <input class="sauvegarde" type="submit" value="Enregistrer">
                            </div>
                        </form>
                    </div>

                    <div class="ensemble_flex ensemble_flex-proprio">
                        <div class="champs_verticals">
                            <form method="post" class="photo_conteneur photo_conteneur-proprio" id="photo_client"
                                enctype="multipart/form-data">
                                <input type="hidden" name="form_type" value="photo">
                                <h3>Votre photo de profil</h3>
                                <img src="/img/<?= $src_photo ?>" alt="photo de profil de l'utilisateur">
                                <div class="changer_photo">
                                    <label for="photo_profile">Changer la photo</label>
                                    <div class="ligne">
                                        <input type="file" id="photo_profile" name="photo_profile" accept="image/*">
                                        <input class="sauvegarde" type="submit" value="Enregistrer">
                                    </div>
                                </div>
                            </form>
                            <form class="mdp_conteneur" id="mdp_client" method="post">
                                <input type="hidden" name="form_type" value="motdepas">
                                <h3>Modifier le mot de passe</h3>
                                <div class="compte__input">
                                    <label for="compte__mdp">Mot de passe actuel :</label>
                                    <div class="ligne">
                                        <input type="password" id="compte__mdp" name="mdp">
                                    </div>
                                </div>
                                <div class="changer__mdp">
                                    <div class="ligne">
                                        <div class="compte__input">
                                            <label for="compte__mdp">Nouveau mot de passe :</label>
                                            <input type="password" id="new__mdp" name="new_mdp"
                                                placeholder="Au moins 8 caractères">
                                        </div>
                                        <div class="compte__input">
                                            <label for="compte__mdp">Confirmez le mot de passe :</label>
                                            <input type="password" id="new__mdp2" name="new_mdp2">
                                        </div>

                                    </div>
                                </div>
                                <?php if ($passwordVerify): ?>
                                    <p class="login_invalid">Mot de passe ivalide !</p>
                                <?php endif; ?>
                                <?php if ($passwordMismatch): ?>
                                    <p class="login_invalid">Les mots de passe ne correspondent pas !</p>
                                <?php endif; ?>
                                <?php if ($passwordLengthInvalid): ?>
                                    <p class="login_invalid">Le mot de passe doit contenir au moins 8 caractères.</p>
                                <?php endif; ?>
                                <input class="sauvegarde" type="submit" value="Enregistrer" style="width:30%;">

                            </form>
                        </div>
                        <div class="champs_verticals">
                            <form class="identite_conteneur" id="identite_client" method="post"
                                enctype="multipart/form-data">
                                <input type="hidden" name="form_type" value="identite">
                                <h3>Vérification de l'identité</h3>
                                <p> Enregistrez les détails de votre passeport pour vérifier votre
                                    identité. Choisissez des photos recto-verso.</p>
                                <div class="input__ligne">
                                    <div class="connect__input">
                                        <label for="connect__recto">Photo du recto</label>
                                        <input type="file" name="photo_recto" id="connect__recto" accept="image/*">
                                        <img src="<?= $recto ?>" alt="Photo du recto" class="ident_photo">
                                    </div>
                                    <div class="connect__input">
                                        <label for="connect__verso">Photo du verso</label>
                                        <input type="file" name="photo_verso" id="connect__verso" accept="image/*">
                                        <img src="<?= $verso ?>" alt="Photo du verso" class="ident_photo">
                                    </div>
                                </div>
                                <input class="sauvegarde" type="submit" value="Enregistrer" style="width:30%;">

                            </form>
                            <form class="paiment_conteneur" id="paiment_client" method="post">
                                <input type="hidden" name="form_type" value="paiment">
                                <h3>Informations de versement</h3>
                                <p>Ajoutez votre RIB </p>
                                <div class="connect__input">
                                    <input type="text" name="iban" id="connect__iban" placeholder="IBAN" required
                                        value="<?= $iban ?>">
                                </div>
                                <div class="connect__input">
                                    <input type="text" name="bic" id="connect_bic" placeholder="BIC" required
                                        value="<?= $bic ?>">
                                </div>
                                <div class="ligne">
                                    <div class="connect__input">
                                        <input type="text" name="titulaire" id="connect_titulaire"
                                            placeholder="Titulaire" required value="<?= $titulaire ?>">
                                    </div>
                                    <input class="sauvegarde" type="submit" value="Enregistrer" style="width:30%;">
                                </div>
                                <?php if ($ibanInvalid): ?>
                                    <p class="login_invalid">IBAN invalide.</p>
                                <?php endif; ?>
                                <?php if ($bicInvalid): ?>
                                    <p class="login_invalid">BIC invalide.</p>
                                <?php endif; ?>
                                <?php if ($accountHolderInvalid): ?>
                                    <p class="login_invalid">Titulaire du compte invalide.</p>
                                <?php endif; ?>


                            </form>
                        </div>

                    </div>


                </div>
            </div>
        </main>
        <?php include "./footer.php"; ?>
    </div>
</body>

</html>