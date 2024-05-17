<?php 
    session_start();            // Démarrage de la session 
    require_once 'db_config.php';  // Connexion à la bdd
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="NoS1gnal"/>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="post_ad.css"> <!-- Inclusion du fichier CSS externe -->
        <title>Post d'annonce</title>
    </head>
    <body>
    <header> <!-- Entete -->
        <a href="accueil.php"><h3> Retour à l'accueil</h3></a> <!-- Retour à l'accueil si l'on ne veut pas poster d'annonce -->         
    </header>
    <div class="login-form">                            <!-- Différentes options pour s'occuper des erreurs et messages d'erreurs -->
        <form action="post_ad_process.php" method="post" enctype="multipart/form-data">         <!-- On associe le processus connexion.php au formulaire, qui recevra les données de manière POST-->
            <h2 class="text-center">Je poste une annonce :</h2>       
            <div class="form-group">
                <h4> Titre : </h4>
                <input type="text" name="titre" class="form-control" placeholder="Titre" required="required" autocomplete="off">       <!-- Entrée du mail-->
            </div>
            <div class="form-group">
                <label>Catégorie :</label>
                <select class="form-control" id="categorie" name="categorie">
                    <?php
                    
                    // Requête pour récupérer les options depuis la base de données
                    $query = $bdd->query('SELECT nom_categorie FROM categories');

                    // Boucle pour afficher chaque option
                    while ($row = $query->fetch()) {
                        echo '<option>' . $row['nom_categorie'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>État :</label>
                <select class="form-control" id="etat" name="etat">
                    <?php
                    
                    // Requête pour récupérer les options depuis la base de données
                    $query = $bdd->query('SELECT nom_etat FROM etats_produit');

                    // Boucle pour afficher chaque option
                    while ($row = $query->fetch()) {
                        echo '<option>' . $row['nom_etat'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Images :</label>
                <br> <sup>(Vous pouvez en sélectionner plusieurs) </sup>
                <input type="file" name="images[]" id="images" multiple>                
            </div>
            <div class="form-group">
                <label>Description :</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Prix :</label>
                <input type="number" id="prix" name="prix" class="form-control" placeholder="En euros"/>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Publier mon annonce</button>      <!-- Bouton d'envoi/de submit du formulaire afin de se connecter -->
            </div>   
        </form>
    </div>
    </body>
</html>
