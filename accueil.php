<!DOCTYPE html> 
<?php 
    session_start();            // Démarrage de la session 
    require_once 'db_config.php';  // Connexion à la bdd
?>

<html>
    <head>

    </head>

    <body>

        <link rel="stylesheet" type="text/css" href="accueil.css">

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
                    echo "<li><a href='favoris.php'>Mes favoris</a></li>";
                    echo "<li><a href='annonces.php'>Mes annonces</a></li>";
                    echo "<li><a href='transactions.php'>Mes transactions</a></li>";
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

        <?php
        // 1. Récupérez les catégories depuis la base de données
        $query = $bdd->query('SELECT nom_categorie FROM categories');

        // 2. Parcourez les résultats et créez les éléments <li> correspondants
        echo '<ul class="menu">';
        while ($row = $query->fetch()) {
            $nom_categorie = $row['nom_categorie'];
            echo '<li>';
            echo '<a href="accueil.php?categorie=' . $nom_categorie . '" id="' . $nom_categorie . '">' . $nom_categorie . '</a>';
            echo '</li>';
        }
        echo '</ul>';
        ?>

    </body>

</html>
