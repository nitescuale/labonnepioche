<?php
session_start();
require_once 'db_config.php';

if(isset($_POST['id_annonce']) && isset($_SESSION['user'])) {
    $id_annonce = $_POST['id_annonce'];

    // Récupérer l'utilisateur acheteur à partir de la session
    $acheteur_query = $bdd->prepare('SELECT id_utilisateur FROM utilisateurs WHERE token = ?');
    $acheteur_query->execute(array($_SESSION['user']));
    $acheteur_data = $acheteur_query->fetch();

    // Vérifier que l'utilisateur acheteur existe
    if (!$acheteur_data) {
        die('Utilisateur acheteur non trouvé');
    }

    $id_acheteur = $acheteur_data['id_utilisateur'];

    // Récupérer l'utilisateur vendeur à partir de l'annonce
    $vendeur_query = $bdd->prepare('SELECT id_utilisateur, prix FROM annonces WHERE id_annonce = ?');
    $vendeur_query->execute(array($id_annonce));
    $vendeur_data = $vendeur_query->fetch();

    // Vérifier que l'annonce et l'utilisateur vendeur existent
    if (!$vendeur_data) {
        die('Annonce ou vendeur non trouvé');
    }

    $id_vendeur = $vendeur_data['id_utilisateur'];
    $montant = $vendeur_data['prix'];
    $date_transaction = date('Y-m-d H:i:s');

    // Afficher les valeurs pour débogage
    echo "ID Acheteur: $id_acheteur<br>";
    echo "ID Vendeur: $id_vendeur<br>";
    echo "ID Annonce: $id_annonce<br>";
    echo "Montant: $montant<br>";
    echo "Date Transaction: $date_transaction<br>";

    // Insérer la transaction dans la base de données
    $insert_query = $bdd->prepare('INSERT INTO transactions (id_annonce, id_vendeur, id_acheteur, montant, date_transaction) VALUES (?, ?, ?, ?, ?)');
    if ($insert_query->execute(array($id_annonce, $id_vendeur, $id_acheteur, $montant, $date_transaction))) {
        echo "Transaction réussie";
    } else {
        echo "Erreur lors de l'insertion de la transaction";
    }
} else {
    echo "Paramètres manquants ou utilisateur non connecté";
    echo $_POST['id_annonce'];
    echo $_SESSION['user'];
}
?>
