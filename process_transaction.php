<?php
session_start();

require_once 'db_config.php';

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['id_annonce']) && isset($_SESSION['user'])) {
    $id_annonce = $_POST['id_annonce'];

    // Récupérer l'utilisateur acheteur à partir de la session
    $acheteur_query = $bdd->prepare('SELECT id_utilisateur, email FROM utilisateurs WHERE token = ?');
    $acheteur_query->execute(array($_SESSION['user']));
    $acheteur_data = $acheteur_query->fetch();

    // Vérifier que l'utilisateur acheteur existe
    if (!$acheteur_data) {
        die('Utilisateur acheteur non trouvé');
    }

    $id_acheteur = $acheteur_data['id_utilisateur'];
    $email_acheteur = $acheteur_data['email'];

    // Récupérer l'utilisateur vendeur à partir de l'annonce
    $vendeur_query = $bdd->prepare('SELECT u.id_utilisateur, u.email, a.prix, a.titre
                                    FROM annonces AS a 
                                    JOIN utilisateurs AS u ON a.id_utilisateur = u.id_utilisateur 
                                    WHERE a.id_annonce = ?');
    $vendeur_query->execute(array($id_annonce));
    $vendeur_data = $vendeur_query->fetch();

    // Vérifier que l'annonce et l'utilisateur vendeur existent
    if (!$vendeur_data) {
        die('Annonce ou vendeur non trouvé');
    }


    $titre_annonce = $vendeur_data['titre'];
    $id_vendeur = $vendeur_data['id_utilisateur'];
    $email_vendeur = $vendeur_data['email'];
    $montant = $vendeur_data['prix'];
    $date_transaction = date('Y-m-d H:i:s');

    // Insérer la transaction dans la base de données
    $insert_query = $bdd->prepare('INSERT INTO transactions (id_annonce, id_vendeur, id_acheteur, montant, date_transaction) VALUES (?, ?, ?, ?, ?)');
    if ($insert_query->execute(array($id_annonce, $id_vendeur, $id_acheteur, $montant, $date_transaction))) {



        $mail = new PHPMailer(true);
        // Paramètres SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Adresse SMTP du serveur
        $mail->SMTPAuth = true;
        $mail->Username = 'nitescuale@cy-tech.fr'; // Adresse e-mail SMTP
        $mail->Password = '-'; // Mot de passe SMTP, à modifier lors de l'utilisation
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $headers = 'From: La Bonne Pioche' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        // Envoyer un email à l'acheteur
        $mail->setFrom('nitescuale@cy-tech.fr', 'La Bonne Pioche');
        $mail->addAddress($email_acheteur, 'Nom du destinataire');
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre achat';
        $mail->Body = "Bonjour,\n\nVous avez acheté l'annonce $titre_annonce pour un montant de $montant €. La transaction a été effectuée le $date_transaction.\n\nMerci pour votre achat. N'hésitez pas à prendre contact avec votre acheteur à l'adresse mail suivante : $email_vendeur pour organiser la livraison/récupération de votre bien";
        $mail->send();

        // Envoyer un email au vendeur
        $mail->setFrom('nitescuale@cy-tech.fr', 'La Bonne Pioche');
        $mail->addAddress($email_vendeur, 'Nom du destinataire');
        $mail->isHTML(true);
        $mail->Subject = 'Bien vendu';
        $mail->Body = "Bonjour,\n\nVotre annonce $titre_annonce a été achetée pour un montant de $montant € par l'utilisateur $id_acheteur. La transaction a été effectuée le $date_transaction.\n\nMerci d'organiser la livraison/récupération de l'objet. Vous pouvez le contacter à l'adresse mail suivante : $email_acheteur";
        $mail->send();

        echo "Transaction réussie et emails envoyés";
    } else {
        echo "Erreur lors de l'insertion de la transaction";
    }
} else {
    echo "Paramètres manquants ou utilisateur non connecté";
}
?>
