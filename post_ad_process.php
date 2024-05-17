<?php 
    session_start();            // Démarrage de la session 
    require_once 'db_config.php';  // Connexion à la bdd

// On vérifie que les variables existent et qu'elles ne sont pas vides
if(!empty($_POST['titre']) && !empty($_POST['categorie']) && !empty($_POST['etat']) && isset($_FILES["images"]["error"]) && !empty($_POST['description']) && !empty($_POST['prix']))
    {
    // Patch XSS, pour éviter une entrée de code à partir des inputs de la page de connexion (injections SQL). On élimine ici les charactères spéciaux ('=&#039 par exemple)
    $titre = htmlspecialchars($_POST['titre']);
    $categorie = htmlspecialchars($_POST['categorie']);
    $etat = htmlspecialchars($_POST['etat']);
    $description = htmlspecialchars($_POST['description']);
    $prix = htmlspecialchars($_POST['prix']);


    // On récupère les données de l'utilisateur connecté. Sinon, on renvoie vers la page de login
    
    if (is_null($_SESSION['user']) == 1){
        header('Location: login.php'); die();
    }

    $check = $bdd->prepare('SELECT id_utilisateur FROM utilisateurs WHERE token = ?');
    $check->execute(array($_SESSION['user']));
    $data = $check->fetch();
    $row = $check->rowCount();

    $id_utilisateur = $data['id_utilisateur'];

    if ($row > 0) {// Si la requete renvoie un 0 alors l'utilisateur n'a pas été retrouvé

        // On enregistre la date de publication
        $date_publication = date('Y-m-d', $_SERVER['REQUEST_TIME']);
        
        // On insère le tout dans la base de données
        $insert = $bdd->prepare('INSERT INTO annonces(id_utilisateur, titre, categorie, etat, description, prix, date_publication) 
                                    VALUES(:id_utilisateur, :titre, :categorie, :etat, :description, :prix, :date_publication)');

        $insert->execute(array(
            'id_utilisateur' => $id_utilisateur,
            'titre' => $titre,
            'categorie'=>$categorie,
            'etat' => $etat,
            'description' => $description,
            'prix' => $prix,
            'date_publication' => $date_publication
        ));
    }

    // Traitement des images

    // Chemin où vous souhaitez stocker les images téléchargées
    $targetDir = "./ad_pics/";

    // Boucle à travers tous les fichiers téléchargés
    foreach ($_FILES["images"]["name"] as $key => $fileName){
        $targetFile = $targetDir . basename($fileName);
        $fileTmpName = $_FILES["images"]["tmp_name"][$key];
    
        // Vérifie si le fichier est une image
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION); // $fileName est utilisé ici pour obtenir l'extension du fichier
    
        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            
            // Insérer l'image dans la bdd
            if (move_uploaded_file($fileTmpName, $targetFile)) {
                $url_photo = 'http://localhost/labonnepioche/ad_pics/' . basename($fileName);
    
                // Récupérer l'id_annonce qui vient d'être crée
                $query = $bdd->query('SELECT id_annonce FROM annonces ORDER BY id_annonce DESC LIMIT 1');
                $id_annonce = $query->fetchColumn();
    
                $insert = $bdd->prepare('INSERT INTO photos_annonces(id_annonce, url_photo) 
                                         VALUES(:id_annonce, :url_photo)');
    
                $insert->execute(array(
                    'id_annonce' => $id_annonce,
                    'url_photo' => $url_photo,
                ));
            } else {
                echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            }
        }
    }
    // On redirige avec le message de succès
    header('Location:post_ad.php?reg_err=success');
}