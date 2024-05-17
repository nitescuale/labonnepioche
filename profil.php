<!DOCTYPE html> 
<?php 
    session_start();            // Démarrage de la session 
    require_once 'db_config.php';  // Connexion à la bdd
?>

<html>
    <head>

    </head>

    <body>

        <link rel="stylesheet" type="text/css" href="profil.css">

        <header> <!-- Entete -->
            <a href="accueil.php"><img   src="logo_site.png" alt="Logo de mon site web" ></a> <!-- Ajout du logo, qui renvoie à l'accueil si cliqué -->
        <div class="search-bar">
                <input type="text" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </div>
            <div class="header-buttons">
                <a href="post_ad.php" class="publish-button">Publier une annonce</a>
                <?php

                // Vérifie si l'utilisateur est connecté
                if (isset($_SESSION['user'])) {

                    // On récupère les données et l'URL de la photo de profil de l'utilisateur depuis la base de données
                    $check = $bdd->prepare('SELECT nom, prenom, email, password, token, id_utilisateur, url_photo_profil FROM utilisateurs WHERE token = ?');
                    $check->execute(array($_SESSION['user']));
                    $data = $check->fetch();

                    // Afficher l'icône de l'utilisateur connecté
                    $user = $_SESSION['user'];
                    $url_photo_profil = $data['url_photo_profil'];
                    // Afficher le menu défilant
                    echo "<div class='menu-dropdown'>";
                    echo "<img id='dropdown-icon' src='$url_photo_profil' alt='Icône utilisateur'>";
                    echo "<ul class='dropdown-content' id='dropdown-content'>";
                    echo "<li><a href='profil.php'>Mon profil</a></li>";
                    echo "<li><a href='mes_favoris.php'>Mes favoris</a></li>";
                    echo "<li><a href='mes_annonces.php'>Mes annonces</a></li>";
                    echo "<li><a href='mes_transactions.php'>Mes transactions</a></li>";
                    echo "<li><a href='logoff.php'>Se déconnecter</a></li>";
                    echo "</ul>";
                    echo "</div>";
                } else {
                    // Afficher l'icône de connexion/inscription par défaut
                    echo "<a href='login.php' class='pfp_login'><img src='login_icon.png' alt='Connexion/Inscription' style='width: 100px; height: 100px; margin-left:-50%; margin-top:25%;'></a>";
                }
                ?>
            </div>
        </header>

        <?php // On récupère les données de l'utilisateur à afficher sur son profil
        $check = $bdd->prepare('SELECT *
                            FROM utilisateurs
                            WHERE token = ?');

        $check->execute(array($_SESSION['user']));
        $row = $check->fetch();
        
        $nom = $row['nom'];
        $prenom = $row['prenom'];
        $email = $row['email'];
        $url_photo_profil = $row['url_photo_profil'];

            echo "<div class=form_donnees style ='margin-left:5%; margin-top:12%;'>";
            echo "<p>Nom : $nom </p>";
            echo "<p>Prénom : $prenom </p>";
            echo "<p>Adresse mail : $email </p>";
            echo "<p>Photo de profil : </p>";
            echo "<img src='$url_photo_profil' alt='Photo de profil' style='width: 250px; height: 250px; margin-left:6%; margin-top:-2%;'>";

        ?>