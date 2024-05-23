<!DOCTYPE html>
<?php 
    session_start();            // Démarrage de la session 
    require_once 'db_config.php';  // Connexion à la bdd
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes annonces</title>
    <link rel="stylesheet" href="mes_annonces.css">
</head>
<body>
    <header>
        <a href="accueil.php" class="logo"><img src="logo.png" alt="Logo du site"></a>
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
                echo "<img id='dropdown-icon' src='$url_photo_profil' alt='Icône utilisateur'>";
                echo "<div class='dropdown-content'>";
                echo "<a href='profil.php'>Mon profil</a>";
                echo "<a href='mes_favoris.php'>Mes favoris</a>";
                echo "<a href='mes_annonces.php'>Mes annonces</a>";
                echo "<a href='logoff.php'>Se déconnecter</a>";
                echo "</div>";
                echo "</div>";
            } else {
                // Afficher l'icône de connexion/inscription par défaut
                echo "<a href='login.php' class='pfp_login'><img src='login.png' alt='Connexion/Inscription' class='login-logo'></a>";
            }
            ?>
        </div>
    </header>
    <h1 class="titre">Mes annonces :</h1>

    <div class="annonces-container">
        <?php
        // Récupérez les annonces et les photos correspondantes à l'utilisateur connecté depuis la base de données
        $check = $bdd->prepare('SELECT a.id_annonce, a.titre, a.prix, a.categorie, a.etat, a.vendu, p.url_photo
                            FROM annonces a
                            JOIN utilisateurs u ON u.id_utilisateur = a.id_utilisateur
                            LEFT JOIN photos_annonces AS p ON a.id_annonce = p.id_annonce
                            WHERE u.token = ?
                            GROUP BY a.id_annonce');
        $check->execute(array($_SESSION['user']));

        // Parcourez les résultats et affichez les annonces avec leurs photos
        while ($row = $check->fetch()) {
            $id_annonce = $row['id_annonce'];
            $titre = $row['titre'];
            $prix = $row['prix'];
            $categorie = $row['categorie'];
            $etat = $row['etat'];
            $url_photo = $row['url_photo'];
            $vendu = $row['vendu'];

            echo '<div class="annonces-container">';
                echo '<div class="annonces-wrapper">';
                    echo '<div class="annonce">';
                        echo '<img src="' . $url_photo . '" alt="' . $titre . '">';
                        echo '<div class="details">';
                            echo '<h2><a class="lien-annonce" href="annonce.php?annonce=' . $id_annonce . '">' . $titre . '</a></h2>';
                            echo '<p class="etat">' . $etat . '</p>';
                            echo '<p class="prix">' . $prix . ' € </p>';
                        echo '</div>';
                        echo '<a href="edit_annonce.php?id_annonce=' . $id_annonce . '" class="edit-button">Modifier</a>';
                        if ($vendu == 0) {
                            echo '<form method="POST" action="mark_as_sold.php">';
                                echo '<input type="hidden" name="annonce_id" value="' . htmlspecialchars($id_annonce) . '">';
                                echo '<button type="submit">Marquer comme vendue</button>';
                            echo '</form>';
                        } else {
                            echo '<p>Cette annonce est vendue</p>';
                        }
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
        ?>
    </div>

    <script>
        function performSearch() {
            var searchText = document.getElementById('search-input').value;
            searchText = capitalizeFirstLetter(searchText.toLowerCase());
            window.location.href = 'http://localhost/labonnepioche/accueil.php?search=' + searchText;
        }

        function capitalizeFirstLetter(text) {
            return text.charAt(0).toUpperCase() + text.slice(1);
        }

        function handleKeyDown(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        }
    </script>
</body>
</html>
