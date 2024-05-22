<?php 
    require_once "../utils.php";
    session_start();

    $status = false;

    // Si déjà connecté on renvoie sur l'acceuil.
    if (isset($_SESSION["client_id"])){
        redirect();
        exit;
    }   

    // Si le formulaie à était envoyé 

    if (isset($_POST["email"]) && isset($_POST["password"])){
        $email = strtolower($_POST["email"]);

        $query = "SELECT sae._utilisateur.id, mot_de_passe, photo_profile FROM sae._compte_client 
        INNER JOIN sae._utilisateur ON sae._compte_client.id = sae._utilisateur.id 
        WHERE email = '$email'";
                
        $result = request($query, true);
        // Si aucun compte trouvé
        if (empty($result)) {
            $status = true;
        } else {
            // On vérifie les mot de passe.
            if (password_verify($_POST["password"], $result["mot_de_passe"])){
                $_SESSION["client_id"] = $result["id"];
                $_SESSION["photo_user"] = $result["photo_profile"];
                redirect();
                exit; 
            } else {
                $status = true;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/connect.css">
    <title>Connexion</title>
    <script src="https://kit.fontawesome.com/7f17ac2dfc.js" crossorigin="anonymous"></script>
</head>
<body class="page">
    <div class="wrapper">
        <?php require_once "header.php" ?>
        <main class="main">
            <div class="main__container">
                <div class="connect-container">
                    <div class="connect__from">
                        <h1>Bienvenue <img src="img/hello.webp" alt="Hello"></h1>
                        <p>Plongez dans l'authenticité bretonne en choisissant parmi une gamme de logements uniques.</p>
                        <form method="POST" action="">
                            <div class="connect__input">
                                <label for="connect__email">Adresse e-mail</label>
                                <input type="email" name="email" id="connect__email" placeholder="Veuillez saisir votre adresse e-mail">
                            </div>
                            <div class="connect__input">    
                                <label for="connect__pass">Mot de passe</label>
                                <input type="password" name="password" id="connect__pass" placeholder="Saisissez un mot de passe">
                            </div>

                            <a class="mdp_oublie" href="">Mot de passe oublié ?</a>

                            <?php if ($status): ?> <p class="login_invalid">Identifiant invalide !</p> <?php endif; ?>

                            <input type="submit" value="Se connecter">
                        </form>
                        <p class="p_ligne">Ou</p>
                        <div class="connect__google"><img src="../img/google.webp" alt="Google"><a href="">Continuer avec Google</a></div>
                        <div class="connect__face"><img src="../img/facebook.webp" alt="Facebook"><a href="">Continuer avec Facebook</a></div>
                        <p style="align-self: center; text-align:center;">Vous n'avez pas de compte ?  <a href="" style="color: #5669FF;">S'inscrire</a></p>
                    </div>
                    <img class="connect__photo" src="../img/sea.webp" alt="Sea">
                </div>
            </div>

        </main>
        <?php require_once "footer.php" ?>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
