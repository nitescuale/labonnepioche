<?php 
    session_start(); // Démarrage de la session
    session_destroy(); // On détruit la session
    header('Location:accueil.php'); // On redirige sur la page d'accueil
    die();