<!DOCTYPE html> 

<html>
    <head>

    </head>

    <body>

        <link rel="stylesheet" type="text/css" href="accueil.css">

        <header> <!-- Entete -->
            <a href="accueil.php"><img   src="La_Bonne_Pioche_2_-removebg-preview.png" alt="Logo de mon site web" ></a> <!-- Ajout du logo, qui renvoie à l'accueil si cliqué -->
            <a id="poster_annonce" href="/labonnepioche/poster_annonce.php">
            <button>Je souhaite publier une annonce</button>
            </a>            
            <div class="menu-dropdown">
                <img id="dropdown-icon" src="user_icon_png_transparent_15_removebg_preview.png" alt="Icône utilisateur">
                <div class="dropdown-content" id="dropdown-content">
                    <a href="profil.php">Mon profil</a>
                    <a href="favoris.php">Mes favoris</a>
                    <a href="annonces.php">Mes annonces</a>
                    <a href="transactions.php">Mes transactions</a>
                </div>
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
