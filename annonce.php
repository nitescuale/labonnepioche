<!DOCTYPE html>
<?php 
    session_start(); // Démarrage de la session 
    require_once 'db_config.php'; // Connexion à la bdd
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="annonce.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <header> <!-- Entete -->
            <a href="accueil.php"><img src="logo.png" alt="Logo de mon site web"></a> <!-- Ajout du logo, qui renvoie à l'accueil si cliqué -->
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
                    echo "<li><a href='mes_annonces.php'>Mes annonces</a></li>";
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

        <div class="menu">
            <ul>
                <li><a href="#">Autres</a></li>
                <li><a href="#">Électronique</a></li>
                <li><a href="#">Emploi</a></li>
                <li><a href="#">Famille</a></li>
                <li><a href="#">Immobilier</a></li>
                <li><a href="#">Location de vacances</a></li>
                <li><a href="#">Loisirs</a></li>
                <li><a href="#">Maison & Jardin</a></li>
                <li><a href="#">Mode</a></li>
                <li><a href="#">Véhicules</a></li>
            </ul>
        </div>

        <?php
        if(isset($_GET['annonce'])) {
            $annonce = htmlspecialchars($_GET['annonce']);

            // Vérifier si l'annonce existe dans la base de données
            $check_query = $bdd->prepare('SELECT COUNT(*) AS count FROM annonces WHERE id_annonce = ?');
            $check_query->execute(array($annonce));
            $result = $check_query->fetch();

            // Vérifier le résultat de la requête
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

                echo '<div class="annonces-container">';
                    echo '<div class="annonces-wrapper">';
                        echo '<div class="annonce">';
                            echo '<div class="slider-container">';
                                echo '<div class="slider">';
                                    $query->execute(array($annonce)); // Réexécutez la requête pour récupérer les URLs des images
                                    while ($row = $query->fetch()) {
                                        $url_photo = $row['url_photo'];
                                        echo '<div class="slide">';
                                        echo '<img class="slide-img" src="' . $url_photo . '">';
                                        echo '</div>';
                                    }
                                echo '</div>';
                                echo '<div class="slider-buttons">';
                                    echo '<button class="prev" onclick="plusSlides(-1)">❮</button>';
                                    echo '<button class="next" onclick="plusSlides(1)">❯</button>';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="details">';
                                echo '<h2>'. $titre . '</h2>';
                                echo '<p class="description">' . $description . '</p>';
                                echo '<p class="etat">' . $etat . '</p>';
                                echo '<p class="prix">' . $prix . ' € </p>';
                                echo '<form method="post">';
                                echo '<input type="hidden" name="id_annonce" value="' . $id_annonce . '">';
                                echo '<input class="favoris-button" type="submit" name="add_to_favorites" value="Ajouter aux favoris">';
                                echo '</form>';
                                echo '<form method="post" action="process_transaction.php">';
                                    echo '<input type="hidden" name="id_annonce" value="' . $id_annonce . '">';
                                    echo '<button type="submit" name="buy_now">J\'achète</button>';
                                echo '</form>';
                            echo '</div>'; // Fin de details
                        echo '</div>'; // Fin de annonce
                    echo '</div>'; // Fin de annonces-wrapper
                echo '</div>'; // Fin de annonces-container

                // Traitement pour ajouter l'annonce aux favoris si le bouton est cliqué
                if (isset($_POST['add_to_favorites'])) {
                    // Vérifier si l'utilisateur est connecté
                    if (isset($_SESSION['user'])) {
                        // Récupérer l'ID de l'utilisateur connecté
                        $id_utilisateur = $data['id_utilisateur']; // Supposant que $data contient les informations de l'utilisateur connecté
                        // Récupérer l'ID de l'annonce à ajouter aux favoris
                        $annonce_id = $_POST['annonce_id'];
                        // Insérer l'annonce dans la table des favoris
                        $insert_query = $bdd->prepare('INSERT INTO favoris (id_utilisateur, id_annonce) VALUES (?, ?)');
                        $insert_query->execute(array($id_utilisateur, $annonce_id));
                        // Afficher un message de succès
                        echo '<p>L\'annonce a été ajoutée aux favoris avec succès.</p>';
                    } else {
                        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
                        header('Location: login.php');
                        exit(); // Arrêter l'exécution du script après la redirection
                    }
                }
            } else {
                // L'annonce n'existe pas
                echo "<h1 class='no_ad'> Cette annonce n'existe pas, retournez à l'accueil </h1>";
            }
        }
        ?>

        <script>
            let slideIndex = 0;
            showSlides(slideIndex);

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function showSlides(n) {
                let slides = document.getElementsByClassName("slide");
                if (n >= slides.length) { slideIndex = 0 }
                if (n < 0) { slideIndex = slides.length - 1 }
                for (let i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                slides[slideIndex].style.display = "block";
            }
        </script>
    </body>
</html>