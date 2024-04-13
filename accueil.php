<!DOCTYPE html> 

<html>
    <head>

    </head>

    <body>

        <link rel="stylesheet" type="text/css" href="accueil.css">

        <header> <!-- Entete -->
            <a href="accueil.php"><img   src="logo_site.png" alt="Logo de mon site web" ></a> <!-- Ajout du logo, qui renvoie à l'accueil si cliqué -->         
            <div class="search-bar">
                <input type="text" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </div>
            <div class="header-buttons">
                <a href="poster_annonce.php" class="publish-button">Publier une annonce</a>
                <?php
                session_start();

                // Fonction pour récupérer l'URL de la photo de profil de l'utilisateur depuis la base de données
                function getUserProfilePictureURL($user_id) {
                    // Code pour récupérer l'URL de la photo de profil de l'utilisateur depuis la base de données
                    // Retourne l'URL de la photo de profil
                }

                // Vérifie si l'utilisateur est connecté
                if (isset($_SESSION['user_id'])) {
                    // Afficher l'icône de l'utilisateur connecté
                    $user_id = $_SESSION['user_id'];
                    $profile_picture_url = getUserProfilePictureURL($user_id);
                    echo "<a href='profil.php' class='pfp_login' ><img src='$profile_picture_url' alt='Photo de profil' style='width: 50%; height: 50%;'></a>";

                    // Afficher le menu défilant
                    echo "<div class='menu-dropdown'>";
                    echo "<img id='dropdown-icon' src='user_icon_png_transparent_15_removebg_preview.png' alt='Icône utilisateur'>";
                    echo "<ul class='dropdown-content' id='dropdown-content'>";
                    echo "<li><a href='profil.php'>Mon profil</a></li>";
                    echo "<li><a href='favoris.php'>Mes favoris</a></li>";
                    echo "<li><a href='annonces.php'>Mes annonces</a></li>";
                    echo "<li><a href='transactions.php'>Mes transactions</a></li>";
                    echo "</ul>";
                    echo "</div>";
                } else {
                    // Afficher l'icône de connexion/inscription par défaut
                    echo "<a href='login.php' class='pfp_login'><img src='login_icon.png' alt='Connexion/Inscription' style='width: 100px; height: 100px; margin-left:-50%; margin-top:25%;'></a>";
                }
                ?>
            </div>
        </header>

        <ul class="menu">  <!-- Ajout des sections sous forme de menu -->
            <li>
                <a href="page4.php" id="jeune">JEUNE</a>      <!-- penser à changer les liens une fois en PHP -->
            </li>
            <li>
                <a href="page5.php" id="referent">R&Eacute;F&Eacute;RENT</a>
            </li>
            <li>
                <a href="page6.php" id="consultant">CONSULTANT</a>
            </li>
            <li>
                <a href="page3.php" id="partenaires">PARTENAIRES</a>
            </li>
        </ul>

    <div id="texte">
        <div>
            <h1> De quoi s&apos;agit-il ? </h1> <!-- Ecriture du texte -->
            <h4> D&apos;une opportunit&eacute; : celle qu&apos;un engagement quel qu&apos;il soit puisse &ecirc;tre <br>
            consid&eacute;rer &agrave; sa juste valeur ! <br>
            Toute exp&eacute;rience est source d&apos;enrichissement et doit d&apos;&ecirc;tre reconnu <br>
            largement. <br>
            Elle r&eacute;v&egrave;le un potentiel, l&apos;expression d&apos;un savoir-&ecirc;tre &agrave; concr&eacute;tiser.</h4>
        </div>
        <div>
            <h1>A qui s&apos;adresse-t&apos;il ? </h1> 
            <h4> A vous, jeunes entre 16 et 30 ans, qui vous &ecirc;tes investis spontan&eacute;ment dans une association ou dans tout type d&apos;action 
                formelle ou informelle, et qui avez partag&eacute; de votre temps, de votre &eacute;nergie, pour apporter un soutien, une aide, une comp&eacute;tence. </h4>
            <h4> A vous, responsables de structures ou r&eacute;f&eacute;rents d&apos;un jour, qui avez crois&eacute; 
            la route de ces jeunes et avez b&eacute;n&eacute;fici&eacute; m&ecirc;me ponctuellement de cette implication citoyenne ! <br>
            C&apos;est l&apos;occasion de vous engager &agrave; votre tour pour ces jeunes en confirmant leur richesse 
            pour en avoir &eacute;t&eacute; un temps les t&eacute;moins mais aussi les b&eacute;n&eacute;ficiaires ! </h4>
        </div>
        <div>
            <h4>A vous, employeurs, recruteurs en ressources humaines, repr&eacute;sentants d&apos;organismes de formation, qui recevez ces jeunes, pour un emploi, un stage,
                un cursus de qualification, pour qui le savoir-&ecirc;tre constitue le premier fondement de toute capacit&eacute; humaine. </h4>
            <h3> Cet engagement est une ressource &agrave; valoriser au fil d'un parcours en 3 &eacute;tapes : </h3>
        </div>
    </div>

    <div id="table">
        <table id="rose"> <!-- construction des trois tableaux -->
            <tr>
                <th align="center" style="color:white";> 1<SUP>&egrave;re</SUP> &eacute;tape<br> la valorisation </th>
            </tr>
            <tr> 
                <td style="color:grey";> D&eacute;crivez votre exp&eacute;rience et mettez en avant ce que vous en avez retir&eacute;. </td>
            </tr>
        </table>

        <table id="vert">
            <tr>
                <th align="center" style="color:white";> 2<SUP>&egrave;me</SUP> &eacute;tape<br>la confirmation</th>
            </tr>
            <tr> 
                <td bgcolor="#9FD8C4" style="color:grey";> Confirmez cette exp&eacute;rience et ce que vous avez pu constater au contact de ce jeune. </td>
            </tr>
        </table>

        <table id="bleu">
            <tr>
                <th align="center" style="color:white";> 3<SUP>&egrave;me</SUP> &eacute;tape<br>la consultation</th>
            </tr>
            <tr> 
                <td style="color:grey";> Validez cet engagement en prenant en compte sa valeur.</td>
            </tr>
        </table>
    </div>

    </body>

</html>
