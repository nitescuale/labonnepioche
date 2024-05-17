<?php 
session_start();            // Démarrage de la session 
require_once 'db_config.php';  // Connexion à la bdd
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'annonce</title>
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="annonce.css">
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
                echo "<a href='mes_transactions.php'>Mes transactions</a>";
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

    <div class="main-content">
        <div class="categorie">
            <ul>
                <?php
                // Récupération des catégories
                $categories_query = $bdd->query('SELECT nom_categorie FROM categories');
                while ($cat = $categories_query->fetch()) {
                    echo '<li><a href="accueil.php?categorie=' . $cat['nom_categorie'] . '">' . $cat['nom_categorie'] . '</a></li>';
                }
                ?>
            </ul>
        </div>
        <?php
        if(isset($_GET['annonce'])) {
            $annonce = htmlspecialchars($_GET['annonce']);

            // Vérifier si l'annonce existe dans la base de données
            $check_query = $bdd->prepare('SELECT COUNT(*) AS count FROM annonces WHERE id_annonce = ?');
            $check_query->execute(array($annonce));
            $result = $check_query->fetch();

            if($result['count'] > 0) {
                // L'annonce existe, récupérez les détails de l'annonce
                $query = $bdd->prepare('SELECT a.id_annonce, a.titre, a.prix, a.description, a.categorie, a.etat, p.url_photo 
                    FROM annonces AS a
                    LEFT JOIN photos_annonces AS p ON a.id_annonce = p.id_annonce
                    WHERE a.id_annonce = ?');
                $query->execute(array($annonce));
                $row = $query->fetch();

                $id_annonce = $row['id_annonce'];
                $titre = $row['titre'];
                $prix = $row['prix'];
                $description = $row['description'];
                $categorie = $row['categorie'];
                $etat = $row['etat'];
                $url_photo = $row['url_photo'];

                echo '<div class="annonce-details">';
                    echo '<div class="slider-container">';
                    echo '<div class="slides">';
                    $query->execute(array($annonce)); // Réexécutez la requête pour récupérer les URLs des images
                    while ($row = $query->fetch()) {
                        $url_photo = $row['url_photo'];
                        echo '<img class="slide-img" src="' . $url_photo . '" alt="' . $titre . '">';
                    }
                    echo '</div>';
                    echo '<button class="prev" onclick="plusSlides(-1)">&#10094;</button>';
                    echo '<button class="next" onclick="plusSlides(1)">&#10095;</button>';
                    echo '</div>';

                    echo '<div class="details">';
                        echo '<h2>'. $titre . '</h2>';
                        echo '<p class="description">' . $description . '</p>';
                        echo '<p class="etat">' . $etat . '</p>';
                        echo '<p class="prix">' . $prix . ' € </p>';
                        echo '<form method="post">';
                        echo '<input type="hidden" name="annonce_id" value="' . $id_annonce . '">';
                        echo '<input type="submit" name="add_to_favorites" value="Ajouter aux favoris">';
                        echo '</form>';
                    echo '</div>'; // Fin de details

                    // Traitement pour ajouter l'annonce aux favoris si le bouton est cliqué
                    if (isset($_POST['add_to_favorites'])) {
                        // Vérifier si l'utilisateur est connecté
                        if (isset($_SESSION['user'])) {
                            // Récupérer l'ID de l'utilisateur connecté
                            $id_utilisateur = $data['id_utilisateur'];
                            // Récupérer l'ID de l'annonce à ajouter aux favoris
                            $annonce_id = $_POST['annonce_id'];

                            // Insérer l'annonce dans la table des favoris
                            $insert_query = $bdd->prepare('INSERT INTO favoris (id_utilisateur, id_annonce) VALUES (?, ?)');
                            $insert_query->execute(array($id_utilisateur, $annonce_id));

                            // Afficher un message de succès
                            echo '<p class="success-msg">L\'annonce a été ajoutée aux favoris avec succès.</p>';
                        } else {
                            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
                            header('Location: login.php');
                            exit(); // Arrêter l'exécution du script après la redirection
                        }
                    }
                echo '</div>'; // Fin de annonce-details
            } else {
                // L'annonce n'existe pas
                echo "<h1 class='no_ad'>Cette annonce n'existe pas, retournez à l'accueil.</h1>";
            }
        }
        ?>
    </div>

    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("slide-img");
            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex-1].style.display = "block";
        }
    </script>
</body>
</html>
