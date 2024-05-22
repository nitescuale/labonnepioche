<?php 
session_start(); // Démarrage de la session 
require_once 'db_config.php'; // Connexion à la bdd
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="accueil.css">
    <script>
        function performSearch() {
            var searchText = document.getElementById('search-input').value;
            searchText = searchText.trim();
            if (searchText !== "") {
                window.location.href = 'accueil.php?search=' + encodeURIComponent(searchText);
            }
        }

        function handleKeyDown(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        }
    </script>
</head>
<body>
    <header> <!-- Entete -->
        <a href="accueil.php"><img src="logo.png" alt="Logo de mon site web"></a> <!-- Ajout du logo, qui renvoie à l'accueil si cliqué -->
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Rechercher..." onkeydown="handleKeyDown(event)">
            <button type="button" onclick="performSearch()">Rechercher</button>
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
                echo "<img id='dropdown-icon' src='$url_photo_profil' alt='Icône utilisateur' class='login-logo'>";
                echo "<ul class='dropdown-content' id='dropdown-content'>";
                echo "<li><a href='profil.php'>Mon profil</a></li>";
                echo "<li><a href='mes_favoris.php'>Mes favoris</a></li>";
                echo "<li><a href='mes_annonces.php'>Mes annonces</a></li>";
                echo "<li><a href='transactions.php'>Mes transactions</a></li>";
                echo "<li><a href='logoff.php'>Se déconnecter</a></li>";
                echo "</ul>";
                echo "</div>";
            } else {
                // Afficher l'icône de connexion/inscription par défaut
                echo "<a href='login.php' class='pfp_login'><img src='login.png' alt='Connexion/Inscription' class='login-logo'></a>";
            }
            ?>
        </div>
    </header>

    <div class="menu">
        <ul>
            <li><a href="accueil.php?categorie=Autres">Autres</a></li>
            <li><a href="accueil.php?categorie=Électronique">Électronique</a></li>
            <li><a href="accueil.php?categorie=Emploi">Emploi</a></li>
            <li><a href="accueil.php?categorie=Famille">Famille</a></li>
            <li><a href="accueil.php?categorie=Immobilier">Immobilier</a></li>
            <li><a href="accueil.php?categorie=Location de vacances">Location de vacances</a></li>
            <li><a href="accueil.php?categorie=Loisirs">Loisirs</a></li>
            <li><a href="accueil.php?categorie=Maison & Jardin">Maison & Jardin</a></li>
            <li><a href="accueil.php?categorie=Mode">Mode</a></li>
            <li><a href="accueil.php?categorie=Véhicules">Véhicules</a></li>
        </ul>
    </div>

    <div class="annonces-container">
        <?php
        // Vérifier si une catégorie est sélectionnée
        if (isset($_GET['categorie'])) {
            $categorie = htmlspecialchars($_GET['categorie']);
            // Requête pour récupérer les annonces de la catégorie sélectionnée
            $query = $bdd->prepare('SELECT a.id_annonce, a.titre, a.prix, a.description, a.categorie, a.etat, p.url_photo 
                FROM annonces AS a
                LEFT JOIN photos_annonces AS p ON a.id_annonce = p.id_annonce
                WHERE a.categorie = ?
                GROUP BY a.id_annonce');
            $query->execute(array($categorie));
        } elseif (isset($_GET['search'])) {
            $search = htmlspecialchars($_GET['search']);
            // Requête pour récupérer les annonces correspondant à la recherche
            $query = $bdd->prepare('SELECT a.id_annonce, a.titre, a.prix, a.description, a.categorie, a.etat, p.url_photo 
                FROM annonces AS a
                LEFT JOIN photos_annonces AS p ON a.id_annonce = p.id_annonce
                WHERE a.titre LIKE ?
                GROUP BY a.id_annonce');
            $query->execute(array('%' . $search . '%'));
        } else {
            // Requête pour récupérer toutes les annonces si aucune catégorie n'est sélectionnée
            $query = $bdd->prepare('SELECT a.id_annonce, a.titre, a.prix, a.description, a.categorie, a.etat, p.url_photo 
                FROM annonces AS a
                LEFT JOIN photos_annonces AS p ON a.id_annonce = p.id_annonce
                WHERE a.vendu = 0
                GROUP BY a.id_annonce');
            $query->execute();
        }

        while ($row = $query->fetch()) {
            $id_annonce = $row['id_annonce'];
            $titre = $row['titre'];
            $prix = $row['prix'];
            $description = $row['description'];
            $categorie = $row['categorie'];
            $etat = $row['etat'];
            $url_photo = $row['url_photo'];

            echo '<div class="annonce">';
                echo '<img src="' . $url_photo . '" alt="' . $titre . '">';
                echo '<div class="details">';
                    echo '<h2><a class="lien-annonce" href="annonce.php?annonce=' . $id_annonce . '">' . $titre . '</a></h2>';
                    echo '<p class="description">' . $description . '</p>';
                    echo '<p class="etat">' . $etat . '</p>';
                    echo '<p class="prix">' . $prix . ' € </p>';
                    echo '<button class="favorite-button">Ajouter aux favoris</button>';
                echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
