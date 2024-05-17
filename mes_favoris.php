<?php 
session_start();            // Démarrage de la session 
require_once 'db_config.php';  // Connexion à la bdd
?>

<!DOCTYPE html>
<html>
<head>

</head>

<body>

<link rel="stylesheet" type="text/css" href="mes_favoris.css">

<header> <!-- Entete -->
    <a href="accueil.php"><img src="logo_site.png" alt="Logo de mon site web"></a> <!-- Ajout du logo, qui renvoie à l'accueil si cliqué -->
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
<h1 class='titre'> Mes favoris :</h1>

<?php

// Récupérer toutes les annonces favorites de l'utilisateur connecté depuis la base de données
$check = $bdd->prepare('SELECT a.id_annonce, a.titre, a.prix, a.categorie, a.etat, p.url_photo
                        FROM annonces a
                        JOIN favoris f ON f.id_annonce = a.id_annonce
                        JOIN utilisateurs u ON u.id_utilisateur = f.id_utilisateur
                        LEFT JOIN photos_annonces AS p ON a.id_annonce = p.id_annonce
                        WHERE u.token = ?
                        GROUP BY a.id_annonce');
$check->execute(array($_SESSION['user']));

// Parcourir les résultats et afficher les annonces avec leurs photos
while ($row = $check->fetch()) {
    $id_annonce = $row['id_annonce'];
    $titre = $row['titre'];
    $prix = $row['prix'];
    $categorie = $row['categorie'];
    $etat = $row['etat'];
    $url_photo = $row['url_photo'];

    echo '<div class="annonces-container">';
    echo '<div class="annonces-wrapper">';
    echo '<div class="annonce">';
    echo '<img src="' . $url_photo . '" alt="' . $titre . '">';
    echo '<div class="details">';
    echo '<h2><a class="lien-annonce" href="annonce.php?annonce=' . $id_annonce . '">' . $titre . '</a></h2>';
    echo '<p class="etat">' . $etat . '</p>';
    echo '<p class="prix">' . $prix . ' € </p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>
</body>
</html>
