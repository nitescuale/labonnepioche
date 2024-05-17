<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user'])) {
    echo "<h1 class='no_ad'>Vous devez être connecté pour modifier une annonce.</h1>";
    exit;
}

if (isset($_GET['id_annonce'])) {
    $id_annonce = htmlspecialchars($_GET['id_annonce']);

    // Récupérer les détails de l'annonce depuis la base de données
    $query = $bdd->prepare('SELECT * FROM annonces WHERE id_annonce = ?');
    $query->execute(array($id_annonce));
    $annonce = $query->fetch();

    if (!$annonce) {
        echo "<h1 class='no_ad'>Cette annonce n'existe pas, retournez à l'accueil</h1>";
        exit;
    }

    // Vérifier que l'annonce appartient à l'utilisateur connecté
    $query = $bdd->prepare('SELECT id_utilisateur FROM annonces WHERE id_annonce = ?');
    $query->execute(array($id_annonce));
    $annonce_owner = $query->fetch();

    $user_check = $bdd->prepare('SELECT nom, prenom, email, password, token, id_utilisateur, url_photo_profil FROM utilisateurs WHERE token = ?');
    $user_check->execute(array($_SESSION['user']));
    $user = $user_check->fetch();

    if ($annonce_owner['id_utilisateur'] !== $user['id_utilisateur']) {
        echo "<h1 class='no_ad'>Vous n'avez pas la permission de modifier cette annonce.</h1>";
        exit;
    }
} else {
    echo "<h1 class='no_ad'>Aucune annonce spécifiée, retournez à l'accueil</h1>";
    exit;
}

// Récupérer les états des produits depuis la table etats_produit
$etats_query = $bdd->query('SELECT id_etat, nom_etat FROM etats_produit');
$etats = $etats_query->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les catégories depuis la table categories
$categories_query = $bdd->query('SELECT id_categorie, nom_categorie FROM categories');
$categories = $categories_query->fetchAll(PDO::FETCH_ASSOC);

// Mise à jour de l'annonce
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $prix = htmlspecialchars($_POST['prix']);
    $categorie = htmlspecialchars($_POST['categorie']);
    $etat = htmlspecialchars($_POST['etat']);
    $description = htmlspecialchars($_POST['description']);

    // Récupérer l'ID de la catégorie et de l'état en fonction du nom
    $cat_query = $bdd->prepare('SELECT id_categorie, nom_categorie FROM categories WHERE nom_categorie = ?');
    $cat_query->execute(array($categorie));
    $cat_result = $cat_query->fetch();

    $etat_query = $bdd->prepare('SELECT id_etat, nom_etat FROM etats_produit WHERE nom_etat = ?');
    $etat_query->execute(array($etat));
    $etat_result = $etat_query->fetch();

    // Mettre à jour l'annonce avec les IDs correspondants
    $update = $bdd->prepare('UPDATE annonces SET titre = ?, prix = ?, categorie = ?, etat = ?, description = ? WHERE id_annonce = ?');
    $update->execute(array($titre, $prix, $cat_result['nom_categorie'], $etat_result['nom_etat'], $description, $id_annonce));

    header('Location: mes_annonces.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier l'annonce</title>
    <link rel="stylesheet" type="text/css" href="edit_annonce.css">
</head>
<body>
    <header>
        <a href="accueil.php" class="logo"><img src="logo.png" alt="Logo du site"></a>
        <ul class="menu">
            <?php
            function fetchCategories($db) {
                $query = $db->query('SELECT nom_categorie FROM categories');
                return $query->fetchAll(PDO::FETCH_ASSOC);
            }
            $categories = fetchCategories($bdd);
            foreach ($categories as $category): ?>
                <li><a href="accueil.php?categorie=<?= htmlspecialchars($category['nom_categorie']) ?>"><?= htmlspecialchars($category['nom_categorie']) ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Rechercher..." onkeydown="handleKeyDown(event)">
            <button type="button" onclick="performSearch()">Rechercher</button>
        </div>
        <div class="header-buttons">
            <a href="post_ad.php" class="publish-button">Publier une annonce</a>
            <div class='menu-dropdown'>
                <img id='dropdown-icon' src='<?= htmlspecialchars($user['url_photo_profil']) ?>' alt='Icône utilisateur'>
                <div class='dropdown-content'>
                    <a href='profil.php'>Mon profil</a>
                    <a href='mes_favoris.php'>Mes favoris</a>
                    <a href='mes_annonces.php'>Mes annonces</a>
                    <a href='mes_transactions.php'>Mes transactions</a>
                    <a href='logoff.php'>Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
    <div class="content">
        <div class="profil-container">
            <h1>Modifier l'annonce</h1>
            <form method="post">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($annonce['titre']) ?>" required><br>

                <label for="prix">Prix :</label>
                <input type="number" id="prix" name="prix" value="<?= htmlspecialchars($annonce['prix']) ?>" required><br>

                <label for="categorie">Catégorie :</label>
                <select id="categorie" name="categorie" required>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= htmlspecialchars($cat['nom_categorie']) ?>" <?= ($cat['nom_categorie'] == $annonce['categorie']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom_categorie']) ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label for="etat">État :</label>
                <select id="etat" name="etat" required>
                    <?php foreach ($etats as $et) : ?>
                        <option value="<?= htmlspecialchars($et['nom_etat']) ?>" <?= ($et['nom_etat'] == $annonce['etat']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($et['nom_etat']) ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label for="description">Description :</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($annonce['description']) ?></textarea><br>

                <button type="submit">Mettre à jour</button>
            </form>
        </div>
    </div>
</body>
</html>
