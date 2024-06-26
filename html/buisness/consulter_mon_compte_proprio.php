<?php
session_start();
require_once "../../utils.php";



if (isset($_POST['valider']) && $_POST['action'] == 'update') {

    $date_fin = (new DateTime($_POST['date_fin']))->format('Y-m-d');
    $date_debut = (new DateTime($_POST['date_debut']))->format('Y-m-d');
    $token = $_POST['token'];
    $id_logements = $_POST['check_logement'] ?? array();

    $sql = 'UPDATE sae._ical_token SET date_debut = \'' . $date_debut . '\', date_fin = \'' . $date_fin . '\' WHERE token = \'' . $token . '\'';

    request($sql);

    $alreadyIn = explode('/', $_POST['alreadyIn']) ?? array();

    //LOGEMENT A SUPPRIMER
    $valuesNotInIdLogements = array_diff($alreadyIn, $id_logements);

    $valuesNotInIdLogements = array_values($valuesNotInIdLogements);
    if (!empty($valuesNotInIdLogements[0])) {
        foreach ($valuesNotInIdLogements as $id) {
            $sql = 'DELETE FROM sae._ical_token_logements WHERE logement = ' . $id;

            request($sql);
        }
    }
    $logementToInsert = array_filter($id_logements, fn ($v) => !in_array($v, $alreadyIn));
    $logementToInsert = array_values($logementToInsert);
    if (!empty($logementToInsert[0])) {
        foreach ($logementToInsert as $id) {
            $sql = 'INSERT INTO sae._ical_token_logements VALUES ';
            $sql .= '(\'' . $token . '\', ' . $id . ')';

            request($sql);
        }
    }
}
if (isset($_POST['valider']) && $_POST['action'] == 'create') {
    $date_fin = (new DateTime($_POST['date_fin']))->format('Y-m-d');
    $date_debut = (new DateTime($_POST['date_debut']))->format('Y-m-d');
    $user_id = (int) $_SESSION['business_id'];
    $id_logements = $_POST['check_logement'] ?? array();
    $sql = 'SELECT sae.generate_ical_token_for_user(' . $user_id;
    $sql .= ', \'' . $date_debut . '\',\'' . $date_fin . '\') as token;';
    $res = request($sql, 0);
    $token = $res[0]['token'];
    //print $token . '<br>';
    foreach ($id_logements as $id) {
        $sql = 'INSERT INTO sae._ical_token_logements VALUES ';
        $sql .= '(\'' . $token . '\', ' . $id . ')';
        request($sql);
    }
}

if (isset($_POST['valider-api']) && $_POST['action'] == 'update'){
    $data = array();
    $api = $_POST['api'];
    $bin = '0000';
    
    foreach($_POST['check_logement'] as $val){
        $data[] = explode('/',$val)[1];
        
    }
    
    if(in_array('admin',$data))$bin[0]='1';
    if(in_array('indispo',$data))$bin[1]='1';
    if(in_array('planning',$data))$bin[2]='1';
    if(in_array('lister',$data))$bin[3]='1';
    
    $sql = ' UPDATE sae._api_keys SET permission = ' . bindec($bin). '::BIT(4)';
    $sql .= ' WHERE key = \'' . $api . '\'';  
    request($sql);
    //print $sql;

    //print '<pre>';
    //print_r($data);
    //print '</pre>';
 
    
}

if (isset($_POST['valider-api']) && $_POST['action'] == 'create'){

    //$api_key = 
    //add_api_key_for_proprietor
    $user_id = (int) $_SESSION['business_id'];
    $data = array();
    $api = $_POST['api'];
    $bin = '0000';
    foreach($_POST['check_logement'] as $val){
        $data[] = explode('/',$val)[1];
        

    }
    if(in_array('admin',$data))$bin[0]='1';
    if(in_array('indispo',$data))$bin[1]='1';
    if(in_array('planning',$data))$bin[2]='1';
    if(in_array('lister',$data))$bin[3]='1';
    
    $sql = 'SELECT sae.add_api_key_for_proprietor(' . $user_id;
    $sql .= ', \'' . $bin . '\') as api;';
   
    $res = request($sql, 0);
    //$api = $res[0]['api'];
    
    
}
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





if ($rep_utilisateur['civilite'] == "Mr") {
    $genre = "Homme";
} else if ($rep_utilisateur['civilite'] == "Mme") {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                                    <input type="date" name="date_naissance" id="compte__date_naissance" value="<?= $date_naissance ?>" readonly>
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



                    <div class="adresse_conteneur">
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
                                <input type="text" name="complement" id="compte__complement1" value="<?= $complement1 ?>" placeholder="Complément" readonly>
                            </div>
                            <div class="compte__input">
                                <label for="compte__complement">Complément d'adresse</label>
                                <input type="text" name="complement" id="compte__complement2" value="<?= $complement2 ?>" placeholder="Complément" readonly>
                            </div>
                            <div class="compte__input">
                                <label for="compte__complement">Complément d'adresse</label>
                                <input type="text" name="complement" id="compte__complement3" value="<?= $complement3 ?>" placeholder="Complément" readonly>
                            </div>
                        </form>
                    </div>
                    <div class="ensemble_flex">
                        <div class="photo_conteneur">
                            <h3>Votre photo de profil</h3>
                            <img src="<?= "../img/" . $photo ?>" alt="photo de profil de l'utilisateur">
                            <!-- <p>source : <?= $src_photo ?></p> -->
                            <!--                         <label for="photo_profile">Votre photo de profil</label>
                            <input type="file" id="photo_profile" name="photo_profile" accept="image/png, image/jpeg" /> -->
                        </div>
                        <div class="sous_ensemble_flex">
                            <div class="mdp_conteneur" style="display: none;">
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
                <div id="api-token">
                    <div id="tokens">
                    <input type="hidden" name="action" value="delete">
                            <div class="token_conteneur">
                                <div class="modal_sup">
                                    <div class="modal" id="modal">
                                        <div class="modal-content">
                                            <p id="text-content">Êtes-vous sûr de vouloir supprimer ?</p>
                                            <div class="modal-actions">

                                                <button type="button" id="cancelBtn">Annuler</button>
                                                <button type="button" name="supp_token" id="confirmBtn">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <form action="" method="POST">
                                    <div class="modal_enreg" id="modal_enreg">
                                        <input type="hidden" name="alreadyIn" id="alreadyIn">
                                        <input type="hidden" name="token" id="token">
                                        <input type="hidden" name="action" id="action" value="">
                                        <div class="modal-content">
                                            <span class="close" id="closeModalBtn">&times;</span>
                                            <h3>Modifier Token</h3>
                                            <div class="dates">
                                                <label for="date_debut">Date début
                                                    <input required="required" type="date" id="date_debut" name="date_debut">
                                                </label>
                                                <label for="date_fin">Date fin
                                                    <input required="required" type="date" id="date_fin" name="date_fin">
                                                </label>
                                            </div>
                                            <div>
                                                <label>
                                                    <input type="checkbox" id="selectAllCheckbox"> Sélectionner tous les logements
                                                </label>
                                            </div>
                                            <div class="logements-container" id="logementsContainer"></div>

                                            <div class="buttons">
                                                <button type="button" class="open-modal-btn" id="closeBtn" style="background-color: #dc3545;">Annuler</button>
                                                <button type="submit" name="valider" class="open-modal-btn">Valider</button>
                                            </div>

                                        </div>
                                </form>


                            </div>
                            <h3>Token Icalendar</h3>
                            <form action="" method="post">
                                <?php
                                $sql = 'SELECT token FROM sae._ical_token where proprietaire =' . $_SESSION["business_id"];
                                $res = request($sql);
                                foreach ($res as $token) :

                                ?>
                                    <div class="token ">
                                        <div>
                                            <input class="token_id" type="password" value="<?= $token['token'] ?>" readonly="readonly">
                                        </div>
                                        <div class="action">
                                            <div class="cross"><i class="fas fa-times"></i></div>
                                            <div class="copier"><i class="fas fa-copy"></i></div>
                                            <div class="modifier"><i class="fas fa-pencil-alt"></i></div>
                                            <div class="eyes"><i class="fas fa-eye"></i></div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                                <button type="button" id="generer_token" value="Générer Token">Générer Token</button>
                            </form>
                    </div>
                    
                    
                </div>
                <div id="api">
                    <input type="hidden" name="action" value="delete">
                    <div class="token_conteneur">
                    
                    
                                <form action="" method="POST">
                                    <div class="modal_enreg" id="modal_enreg-api">
                                        
                                        <input type="hidden" name="api" id="api-key">
                                        <input type="hidden" name="action" id="action-api" value="">
                                        <div class="modal-content">
                                            <span class="close" id="closeModalBtn-api">&times;</span>
                                            <h3>Modifier API</h3>
                                            
                                            <div>
                                                <label>
                                                    <input type="checkbox" id="selectAllCheckbox-api"> Sélectionner tous les droits
                                                </label>
                                            </div>
                                            <div class="droits-container" id="droitsContainer"></div>

                                            <div class="buttons">
                                                <button type="button" class="open-modal-btn" id="closeBtn-api" style="background-color: #dc3545;">Annuler</button>
                                                <button type="submit" name="valider-api" class="open-modal-btn">Valider</button>
                                            </div>

                                        </div>
                                </form>
                                </div>


                            </div>
                            <h3>Clé API</h3>
                            <form action="" method="post">
                                <?php
                                $sql = 'SELECT key FROM sae._api_keys where proprietaire =' . $_SESSION["business_id"];
                                $res = request($sql);
                                foreach ($res as $token) :

                                ?>
                                    <div class="token ">
                                        <div>
                                            <input class="api_id" type="password" value="<?= $token['key'] ?>" readonly="readonly">
                                        </div>
                                        <div class="action">
                                            <div class="cross-api"><i class="fas fa-times"></i></div>
                                            <div class="copier-api"><i class="fas fa-copy"></i></div>
                                            <div class="modifier-api"><i class="fas fa-pencil-alt"></i></div>
                                            <div class="eyes-api"><i class="fas fa-eye"></i></div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                                <button type="button" id="generer_api" value="Générer clé API">Générer clé API</button>
                            </form>
                    </div>
            </div>
    </div>
    </main>
    <?php include "./footer.php";
    ?>
    <?php
    $serverName = $_SERVER['SERVER_NAME']  . ':' . $_SERVER['SERVER_PORT'] . '/ical/ical.php?token=';
    $business_id = $_SESSION['business_id'];
    print <<<EOT
        <script>
            const BUSINESS_ID = '{$business_id}';
            const SERVER_NAME = '{$serverName}';
        </script>
EOT;
     ?>
    

    </div>
    <script src='../js/mon_compte.js'>
        

        //
    </script>
</body>

</html>