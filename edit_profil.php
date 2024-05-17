<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$data = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
$data->execute([$_SESSION['user']]);
$user = $data->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);

    // Handling the profile photo upload
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == 0) {
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $fileName = $_FILES["photo_profil"]["name"];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $targetDir = "./pfps/";
        $targetFile = $targetDir . basename($_FILES["photo_profil"]["name"]);

        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            if (move_uploaded_file($_FILES["photo_profil"]["tmp_name"], $targetFile)) {
                $url_photo_profil = 'http://localhost/labonnepioche/pfps/' . basename($_FILES["photo_profil"]["name"]);
            } else {
                $url_photo_profil = $user['url_photo_profil'];
            }
        } else {
            $url_photo_profil = $user['url_photo_profil'];
        }
    } else {
        $url_photo_profil = $user['url_photo_profil'];
    }

    $update = $bdd->prepare('UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, url_photo_profil = ? WHERE token = ?');
    $update->execute([$nom, $prenom, $email, $url_photo_profil, $_SESSION['user']]);

    header('Location: profil.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil - La Bonne Pioche</title>
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="profil.css">
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
            <h1>Modifier Mon Profil</h1>
            <form method="post" enctype="multipart/form-data">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required><br>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required><br>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

                <label for="photo_profil">Photo de profil :</label>
                <input type="file" id="photo_profil" name="photo_profil"><br>

                <button type="submit">Mettre à jour</button>
            </form>
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
