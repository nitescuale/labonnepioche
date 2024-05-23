<?php 
    session_start(); // Démarrage de la session
    require_once 'db_config.php'; // On inclut la connexion à la base de données

    if(!empty($_POST['email']) && !empty($_POST['password'])) // On vérifie que email et password existent, et s'il ne sont pas vides
    {
        // Patch XSS, pour éviter une entrée de code à partir des inputs de la page de connexion (injections SQL). On élimine ici les charactères spéciaux ('=&#039 par exemple)
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $email = strtolower($email); // Email transformé en minuscule, pour éviter TesT@test.com différent de test@test.com
        $hash_password = hash('sha256', $password.$email);
        
        // On regarde si l'utilisateur est inscrit dans la table utilisateurs
        $check = $bdd->prepare('SELECT nom, prenom, email, password, token, id_utilisateur FROM utilisateurs WHERE email = ?');
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();
        
        // Vérifications puis connexion sur la page d'accueil + session adéquate
        // Si > à 0 alors l'utilisateur existe
        if($row > 0)
        {
            // Si le mail est de format valide
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                // Si le mot de passe est le bon
                if($hash_password == $data['password'])
                {
                    // On crée la session et on redirige à l'accueil.php
                    $_SESSION['user'] = $data['token'];
                    $_SESSION['id'] = $data['id_utilisateur'];
                    header('Location: accueil.php');
                    die();
                }else{ header('Location: login.php?login_err=password'); die(); }       // Divers codes d'erreur à afficher pour chaque cas sur la page index.php
            }else{ header('Location: login.php?login_err=email'); die(); }
        }else{ header('Location: login.php?login_err=already'); die(); }
    }else{ header('Location: login.php'); die();} // Si le formulaire est envoyé sans aucune données