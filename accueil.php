<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>La Bonne Pioche</title>
    <link rel="stylesheet" type="text/css" href="accueil.css">
</head>
<body>
    <?php
    session_start(); // Démarrage de la session
    require_once 'db_config.php'; // Connexion à la base de données

    // Récupération des catégories depuis la base de données
    function fetchCategories($db) {
        $query = $db->query('SELECT nom_categorie FROM categories');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupération des annonces, avec filtre optionnel par catégorie
    function fetchAnnonces($db, $categorie = null) {
        if ($categorie) {
            $stmt = $db->prepare('SELECT a.id_annonce, a.titre, a.prix, a.categorie, a.etat, p.url_photo 
                                  FROM annonces AS a 
                                  LEFT JOIN photos_annonces AS p ON a.id_annonce = p.id_annonce
                                  WHERE a.categorie = ?
                                  GROUP BY a.id_annonce');
            $stmt->execute([$categorie]);
        } else {
            $stmt = $db->query('SELECT a.id_annonce, a.titre, a.prix, a.categorie, a.etat, p.url_photo 
                                FROM annonces AS a
                                LEFT JOIN photos_annonces AS p ON a.id_annonce = p.id_annonce
                                GROUP BY a.id_annonce');
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $categories = fetchCategories($bdd);
    $annonces = fetchAnnonces($bdd, $_GET['categorie'] ?? null);
    ?>

    <header>
        <a href="accueil.php" class="logo"><img src="logo.png" alt="Logo du site"></a>
        <ul class="menu">
            <?php foreach ($categories as $category): ?>
                <li><a href="accueil.php?categorie=<?= $category['nom_categorie'] ?>"><?= $category['nom_categorie'] ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div class="search-bar">
            <input type="text" placeholder="Rechercher...">
            <button type="submit">Rechercher</button>
        </div>
        <div class="header-buttons">
            <a href="post_ad.php" class="publish-button">Publier une annonce</a>
            <?php if (isset($_SESSION['user'])): ?>
                <?php 
                    $data = $bdd->prepare('SELECT url_photo_profil FROM utilisateurs WHERE token = ?');
                    $data->execute([$_SESSION['user']]);
                    $user = $data->fetch(); 
                ?>
                <div class='menu-dropdown'>
                    <img id='dropdown-icon' src='<?= $user['url_photo_profil'] ?>' alt='Icône utilisateur'>
                    <ul class='dropdown-content'>
                        <li><a href='profil.php'>Mon profil</a></li>
                        <li><a href='mes_favoris.php'>Mes favoris</a></li>
                        <li><a href='mes_annonces.php'>Mes annonces</a></li>
                        <li><a href='mes_transactions.php'>Mes transactions</a></li>
                        <li><a href='logoff.php'>Se déconnecter</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href='login.php' class='pfp_login'><img src='login.png' alt='Connexion/Inscription' class="login-logo"></a>
            <?php endif; ?>
        </div>
    </header>

    <div class="annonces-container">
        <?php foreach ($annonces as $annonce): ?>
            <div class="annonce">
                <img src="<?= $annonce['url_photo'] ?>" alt="<?= $annonce['titre'] ?>">
                <div class="details">
                    <h2><a href="annonce.php?annonce=<?= $annonce['id_annonce'] ?>"><?= $annonce['titre'] ?></a></h2>
                    <p class="etat"><?= $annonce['etat'] ?></p>
                    <p class="prix"><?= $annonce['prix'] ?> €</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
