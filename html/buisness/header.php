<?php
    if (session_status() === PHP_SESSION_NONE){session_start();}
    if (!isset($_SESSION["business_id"])){
?>
    <header class="header buisness">
        <div class="header__container">
            <div class="header__nav">
                <div class="header__logo">
                    <a href="index.php"><img src="../img/trisquel.webp" alt="Logo trisquel"></a>
                    <a href="index.php" class="header__name" style="color: white;">ALHaiZ Breizh<span style="color: #FFD33C; text-transform: capitalize;"> Pro</span></a>
                </div>
            </div>
            <div class="header__form">
                <a href=""><i class="fa-regular fa-eye-slash"></i></a>
                <div class="header__connexion"><a href="login.php">Connexion</a></div>                    
            </div>
            <img src="../img/menu.webp" alt="Afficher/Masquer le Menu" id="CmdMenu">
            <img src="../img/fermer.webp" alt="Fermer le menu" id="CmdMenuClose">

        </div>
    </header>
<?php 
    } else {
        require_once '../../utils.php';
        $id = $_SESSION["business_id"];
        $photo_profil = $_SESSION["business_photo"];
        $user_info = request("SELECT pseudo, photo_profile FROM sae._compte_proprietaire
        NATURAL JOIN sae._utilisateur
        WHERE id = '$id'", true);
?>

<header class="header buisness">
<div class="header__container">
    <div class="header__nav">
        <div class="header__logo">
            <a href="index.php"><img src="../img/trisquel.webp" alt="Logo trisquel"></a>
            <a href="index.php" class="header__name">ALHaiZ Breizh<span style="color:#FFD33C; text-transform: capitalize;"> Pro</span></a>
        </div>
    </div>
    <div class="header__form">
        <a href=""><i class="fa-regular fa-eye-slash"></i></a>
            <div class="user__info" id="header__info">
                <img src="../img/<?= $photo_profil?>" alt="Photo User" class="user__photo">
                <p><?= $user_info["pseudo"] ?></p>
                <img src="../img/fleche.webp" alt="Ouvrir le menu" class="user__down">
            </div>                   
    </div>
    <ul class="header__menu-user" id="menu-user">
        <img src="../img/fermer.webp" alt="Fermer le menu" id="fermerMenu">
        <li class="menu__item ">
            <a href="consulter_mon_compte_proprio.php" class="menu__link">Mon compte</a>
        </li>
        <li class="menu__item ">
            <a href="index.php" class="menu__link">Mes Logements</a>
        </li>
        <li class="menu__item ">
            <a href="mes_reserv.php" class="menu__link">Les Réservations</a>
        </li>
        <li class="menu__item ">
            <a href="creer-logement.php" class="menu__link" style="color: #5669FF;">Ajouter un logement</a>
        </li>
        <li class="menu__item ">
            <a href="logout.php" class="menu__link" style="color: #FF5656;">Se déconnecter</a>
        </li>
    </ul> 
    <img src="../img/menu.webp" alt="Afficher/Masquer le Menu" id="CmdMenu">
    <img src="../img/fermer.webp" alt="Fermer le menu" id="CmdMenuClose">
</div>
</header>
<script src="../js/header_user.js"></script>
<?php 
    }
?>