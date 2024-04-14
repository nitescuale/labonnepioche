<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="author" content="NoS1gnal"/>

            <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <title>Inscription</title>
        </head>
        <body>
        <div class="login-form">
            <?php 
                if(isset($_GET['reg_err']))             // Divers cas d'erreur à traiter, messages d'erreur/succès
                {
                    $err = htmlspecialchars($_GET['reg_err']);

                    switch($err)
                    {
                        case 'success':
                        ?>
                            <div class="alert alert-success">
                                <strong>Succès</strong> Inscription réussie !
                            </div>
                            <?php header ('Location: login.php?login_err=signup_success')?>
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
                        case 'prenom_length':
                            ?>
                                <div class="alert alert-danger">
                                    <strong>Erreur</strong> Prénom trop long
                                </div>
                            <?php 
                        case 'already':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> Compte déjà existant
                            </div>
                        <?php 

                    }
                }
                ?>
            
            <form action="signup_process.php" method="post"> <!-- On associe le processus inscription_traitement.php au formulaire, qui recevra les données de manière POST-->
                <h2 class="text-center">Inscription</h2>             <!-- Diverses entrées, de plusieurs types -->
                <div class="form-group">
                    <input type="text" name="nom" class="form-control" placeholder="Nom" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="text" name="prenom" class="form-control" placeholder="Prenom" required="required" autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label for="exampleFormControlFile1">Photo de profil</label>
                    <input type="file" class="form-control-file" id="pfp" name = "pfp">
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
        <style>                     /* Style CSS de la page, assez court pour ne pas prendre la peine de faire un .css externe */
            .login-form {
                width: 340px;
                margin: 50px auto;
            }
            .login-form form {
                margin-bottom: 15px;
                background: #f7f7f7;
                box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
                padding: 30px;
            }
            .login-form h2 {
                margin: 0 0 15px;
            }
            .form-control, .btn {
                min-height: 38px;
                border-radius: 2px;
            }
            .btn {        
                font-size: 15px;
                font-weight: bold;
            }
        </style>
        </body>
</html>
