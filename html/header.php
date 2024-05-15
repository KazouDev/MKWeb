<link rel="stylesheet" href="../css/header.css">

<?php
    session_start();
    if (!isset($_SESSION["client_id"])){
?>
    <header class="header">
        <div class="header__container">
            <div class="header__nav">
                <div class="header__logo">
                    <a href=""><img src="../img/trisquel.webp" alt="Logo trisquel"></a>
                    <a href="" class="header__name">ALHaiZ Breizh</a>
                </div>
                <nav class="header__menu" id="LeMenu">
                    <ul class="menu__list" id="menu__list">
                        <li class="menu__item">
                            <a href="" class="menu__link">Logements</a>
                        </li>
                        <li class="menu__item">
                            <a href="" class="menu__link">À propos</a>
                        </li>
                        <li class="menu__item">
                            <a href="" class="menu__link">Contact</a>
                        </li>
                        <li class="menu__item hide">
                            <a href="" class="menu__link" style="color: #5669FF;">Connexion</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="header__form">
                <a href=""><i class="fa-regular fa-eye-slash" style="color: #222222;"></i></a>
                <div class="header__connexion"><a href="login.php">Connexion</a></div>                    
            </div>
            <img src="../img/menu.webp" alt="Afficher/Masquer le Menu" id="CmdMenu">
            <img src="../img/fermer.webp" alt="Fermer le menu" id="CmdMenuClose">

        </div>
    </header>
    <script src="../js/header.js"></script>
<?php 
    } else {
        require_once '../utils.php';
        $id = $_SESSION["client_id"];
        $user_info = request("SELECT pseudo, photo_profile FROM sae._compte_client
        NATURAL JOIN sae._utilisateur
        WHERE id = '$id'", true);
?>

<header class="header">
<div class="header__container">
    <div class="header__nav">
        <div class="header__logo">
            <a href=""><img src="../img/trisquel.webp" alt="Logo trisquel"></a>
            <a href="" class="header__name">ALHaiZ Breizh</a>
        </div>
        <nav class="header__menu menu" id="LeMenu">
            <ul class="menu__list" id="menu__list">
                <li class="menu__item">
                    <a href="" class="menu__link">Logements</a>
                </li>
                <li class="menu__item">
                    <a href="" class="menu__link">À propos</a>
                </li>
                <li class="menu__item">
                    <a href="" class="menu__link">Contact</a>
                </li>
                <li class="menu__item hide">
                    <a href="" class="menu__link">Mon compte</a>
                </li>
                <li class="menu__item hide">
                    <a href="" class="menu__link">Mes réservations</a>
                </li>
                <li class="menu__item hide">
                    <a href="" class="menu__link">Mes notifications</a>
                </li>
                <li class="menu__item hide">
                    <a href="" class="menu__link" style="color: #5669FF;">Ajouter mon établissement</a>
                </li>
                <li class="menu__item hide">
                    <a href="" class="menu__link" style="color: #FF5656;">Se déconnecter</a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="header__form">
        <a href=""><i class="fa-regular fa-eye-slash" style="color: #222222;"></i></a>
            <div class="user__info" id="header__info">
                <img src="../img/user.webp" alt="Photo User" class="user__photo">
                <p><?= $user_info["pseudo"] ?></p>
                <img src="../img/fleche.webp" alt="Ouvrir le menu" class="user__down">
            </div>                   
    </div>
    <ul class="header__menu-user" id="menu-user">
        <img src="../img/fermer.webp" alt="Fermer le menu" id="fermerMenu">
        <li class="menu__item ">
            <a href="" class="menu__link">Mon compte</a>
        </li>
        <li class="menu__item ">
            <a href="" class="menu__link">Mes réservations</a>
        </li>
        <li class="menu__item ">
            <a href="" class="menu__link">Mes notifications</a>
        </li>
        <li class="menu__item ">
            <a href="" class="menu__link" style="color: #5669FF;">Ajouter mon établissement</a>
        </li>
        <li class="menu__item ">
            <a href="" class="menu__link" style="color: #FF5656;">Se déconnecter</a>
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