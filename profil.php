<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - La Bonne Pioche</title>
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="profil.css">
</head>
<body>
    <?php
    session_start();
    require_once 'db_config.php';

    // Définir la fonction fetchCategories
    function fetchCategories($db) {
        $query = $db->query('SELECT nom_categorie FROM categories');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }

    $data = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
    $data->execute([$_SESSION['user']]);
    $user = $data->fetch();
    ?>

    <header>
        <a href="accueil.php" class="logo"><img src="logo.png" alt="Logo du site"></a>
        <ul class="menu">
            <?php
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
                    <a href='logoff.php'>Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="profil-container">
            <h1>Mon Profil</h1>
            <div class="profil-details">
                <img src="<?= htmlspecialchars($user['url_photo_profil']) ?>" alt="Photo de profil">
                <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                <a href="edit_profil.php" class="edit-button">Modifier mon profil</a>
            </div>
        </div>
    </div>

    <script>
        function performSearch() {
            var searchText = document.getElementById('search-input').value;
            searchText = capitalizeFirstLetter(searchText.toLowerCase());
            window.location.href = 'http://localhost/labonnepioche/accueil.php?search=' + encodeURIComponent(searchText);
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
