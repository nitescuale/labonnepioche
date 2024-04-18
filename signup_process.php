<?php 
    // Ici, pas de session à démarrer vu que personne n'est connecté à ce stade
    require_once 'db_config.php'; // On inclut la connexion à la bdd

    // On vérifie que les variables existent et qu'elles ne sont pas vides
    if(!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_retype']))
    {
        // Patch XSS, pour éviter une entrée de code à partir des inputs de la page de connexion (injections SQL). On élimine ici les charactères spéciaux ('=&#039 par exemple)
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $password_retype = htmlspecialchars($_POST['password_retype']);


        
        if(empty($_FILES['photo_profil'])){
            $url_photo_profil = 'http://localhost/labonnepioche/pfps/user_icon.png';
        }else{
            // Chemin où vous souhaitez stocker les images téléchargées
            $targetDir = "./pfps/";

            // Chemin complet du fichier téléchargé
            $targetFile = $targetDir . basename($_FILES["photo_profil"]["name"]);

            // Vérifie si le fichier est une image réelle ou une fausse image
            $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
            $fileName = $_FILES["photo_profil"]["name"];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                // Le fichier est une image valide, procédez au téléchargement
                if (move_uploaded_file($_FILES["photo_profil"]["tmp_name"], $targetFile)) {
                    $url_photo_profil = 'http://localhost/labonnepioche/pfps/' . basename($_FILES["photo_profil"]["name"]);
                }
            } else {
                $url_photo_profil = 'http://localhost/labonnepioche/pfps/user_icon.png';
            }
        };

        // On vérifie si l'utilisateur existe
        $check = $bdd->prepare('SELECT nom, prenom, email, password FROM utilisateurs WHERE email = ?');
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();

        $email = strtolower($email); // Email transformé en minuscule, pour éviter TesT@test.com différent de test@test.com
        
        if($row == 0){ // Si la requete renvoie un 0 alors l'utilisateur n'existe pas 
        if(strlen($nom) <= 100){ // On verifie que la longueur du nom <= 100
            if(strlen($prenom) <= 100){
                    if(strlen($email) <= 100){ // On verifie que la longueur du mail <= 100
                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Si l'email est de la bonne forme
                            if($password === $password_retype){ // Si les deux mdp saisis sont bon

                                // On hash le mot de passe, + salt avec le mail pour éviter les dictionnaires

                                $hash_password = hash('sha256', $password.$email);


                                // On stock l'adresse IP, on créé le token et on enregistre la dated'inscription
                                $ip = $_SERVER['REMOTE_ADDR'];
                                $date_inscription = date('Y-m-d', $_SERVER['REQUEST_TIME']);
                                $token = bin2hex(openssl_random_pseudo_bytes(64));
                                
                                // On insère le tout dans la base de données
                                $insert = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, email, password, ip, token, url_photo_profil, date_inscription) VALUES(:nom, :prenom, :email, :password, :ip, :token, :url_photo_profil, :date_inscription)');
                                $insert->execute(array(
                                    'nom' => $nom,
                                    'prenom'=>$prenom,
                                    'email' => $email,
                                    'password' => $hash_password,
                                    'ip' => $ip,
                                    'token' => $token,
                                    'url_photo_profil' => $url_photo_profil,
                                    'date_inscription' => $date_inscription
                                ));
                                // On redirige avec le message de succès
                                header('Location:signup.php?reg_err=success');
                            }else{ header('Location: signup.php?reg_err=password'); die();}    // Diverses redirections d'erreur
                        }else{ header('Location: signup.php?reg_err=email'); die();}
                    }else{ header('Location: signup.php?reg_err=email_length'); die();}
                }else{ header('Location: signup.php?reg_err=prenom_length'); die();}
            }else{ header('Location: signup.php?reg_err=nom_length'); die();}
        }else{ header('Location: signup.php?reg_err=already'); die();}
    }