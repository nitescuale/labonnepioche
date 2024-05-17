<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="NoS1gnal"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="accueil.css"> <!-- Inclure le CSS principal -->
    <link rel="stylesheet" href="signup.css"> <!-- Inclure le CSS spécifique à la page d'inscription -->
    <title>Inscription</title>
</head>
<body>
    <?php
    session_start();
    require_once 'db_config.php';

    // Récupération des catégories depuis la base de données
    function fetchCategories($db) {
        $query = $db->query('SELECT nom_categorie FROM categories');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>

    <header>
        <a href="accueil.php" class="logo"><img src="logo.png" alt="Logo du site"></a>
        <ul class="menu">
            <?php
            $categories = fetchCategories($bdd);
            foreach ($categories as $category): ?>
                <li><a href="accueil.php?categorie=<?= $category['nom_categorie'] ?>"><?= $category['nom_categorie'] ?></a></li>
            <?php endforeach; ?>
        </ul>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Rechercher..." onkeydown="handleKeyDown(event)">
            <button type="button" onclick="performSearch()">Rechercher</button>
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
                    <div class='dropdown-content'>
                        <a href='profil.php'>Mon profil</a>
                        <a href='mes_favoris.php'>Mes favoris</a>
                        <a href='mes_annonces.php'>Mes annonces</a>
                        <a href='mes_transactions.php'>Mes transactions</a>
                        <a href='logoff.php'>Se déconnecter</a>
                    </div>
                </div>
            <?php else: ?>
                <a href='login.php' class='pfp_login'><img src='login.png' alt='Connexion/Inscription' class="login-logo"></a>
            <?php endif; ?>
        </div>
    </header>

    <div class="content">
        <div class="login-form">
            <?php 
                if(isset($_GET['reg_err'])) {
                    $err = htmlspecialchars($_GET['reg_err']);

                    switch($err) {
                        case 'success':
                            ?>
                            <div class="alert alert-success">
                                <strong>Succès</strong> Inscription réussie !
                            </div>
                            <?php header ('Location: login.php?login_err=signup_success') ?>
                            <?php
                            break;

                        case 'password':
                            ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> Mot de passe différent
                            </div>
                            <?php
                            break;

                        case 'email':
                            ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> Email non valide
                            </div>
                            <?php
                            break;

                        case 'email_length':
                            ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> Email trop long
                            </div>
                            <?php 
                            break;

                        case 'nom_length':
                            ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> Nom trop long
                            </div>
                            <?php 
                            break;

                        case 'prenom_length':
                            ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> Prénom trop long
                            </div>
                            <?php 
                            break;

                        case 'already':
                            ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> Compte déjà existant
                            </div>
                            <?php 
                            break;
                    }
                }
            ?>
            
            <form action="signup_process.php" method="post" enctype="multipart/form-data">
                <h2 class="text-center">Inscription</h2>
                <div class="form-group">
                    <input type="text" name="nom" class="form-control" placeholder="Nom" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="prenom" class="form-control" placeholder="Prenom" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Photo de profil</label>
                    <input type="file" class="form-control-file" id="photo_profil" name="photo_profil">
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="password" name="password_retype" class="form-control" placeholder="Re-tapez le mot de passe" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Inscription</button>
                </div>   
            </form>
        </div>
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
