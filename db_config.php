<?php 
        /*
           Attention ! host est l'adresse de la base de données, non du site !
        
         */
    try 
    {
        $bdd = new PDO("mysql:host=localhost;dbname=labonnepioche;charset=utf8", "root", "");
        
        /* Connexion à la base de données entrée en paramètre, remplacez projet, "root","", respectivement par votre base de données, nom d'utilisateur et mot de passe 
        (ici laissé "root" et "" mais évidemment renommé "CHANGE_USER" et "CHANGE_PASSWORD" dans un contexte plus professionnel) */

    }
    catch(PDOException $e)          // On gère une quelconque erreur à la connexion à la base de données
    {
        die('Erreur : '.$e->getMessage());
    }
?>