<!DOCTYPE html> 
<?php 
    session_start();            // Démarrage de la session 
    require_once 'db_config.php';  // Connexion à la bdd
?>

<html>
    <head>

    </head>

    <body>

        <link rel="stylesheet" type="text/css" href="annonce.css">

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
                    echo "<li><a href='mes_annonces.php'>Mes annonces</a></li>";
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
        if(isset($_GET['annonce']))
        {
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
                $description=$row['description'];
                $categorie = $row['categorie'];
                $etat = $row['etat'];
                $url_photo = $row['url_photo'];

                echo '<div class="annonces-container">';
                    echo '<div class="annonces-wrapper">';
                        echo '<div class="annonce">';

                        echo '<div class="slider-container">';
                        echo '<div class="menu">';
                        $query->execute(array($annonce)); // Réexécutez la requête pour compter le nombre d'images
                        $nb_images = $query->rowCount(); // Nombre d'images liées à cette annonce
                        for ($i = 1; $i <= $nb_images; $i++) {
                            echo '<label for="slide-dot-' . $i . '"></label>';
                        }
                        echo '</div>';
                    
                        $i = 1;
                        $query->execute(array($annonce)); // Réexécutez la requête pour récupérer les URLs des images
                        while ($row = $query->fetch()) {
                            $url_photo = $row['url_photo'];
                            echo '<input class="slide-input" id="slide-dot-' . $i . '" type="radio" name="slides" ' . ($i == 1 ? 'checked' : '') . '>';
                            echo '<img class="slide-img" src="' . $url_photo . '">';
                            $i++;
                        }
                    
                        echo '</div>'; // Fin de slider-container
                        echo '<div class="details">';
                            echo '<h2>'. $titre . '</h2>';
                            echo '<p class="description">' . $description . '</p>';
                            echo '<p class="etat">' . $etat . '</p>';
                            echo '<p class="prix">' . $prix . ' € </p>';
                            // Bouton "Ajouter aux favoris"
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
                        echo '</div>'; // Fin de details
                        echo '</div>'; // Fin de annonce
                        echo '</div>'; // Fin de annonces-wrapper
                        echo '</div>'; // Fin de annonces-container
                    } else {
                        // L'annonce n'existe pas
                        echo "<h1 class='no_ad'> Cette annonce n'existe pas, retournez à l'accueil </h1>";
                    }
        }
        ?>
    </body>