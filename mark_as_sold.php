<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['annonce_id'])){
    $id_annonce = $_POST['annonce_id'];
    $user_token = $_SESSION['user'];

    // Récupérer l'ID de l'utilisateur connecté
    $user_query = $bdd->prepare('SELECT id_utilisateur FROM utilisateurs WHERE token = ?');
    $user_query->execute(array($user_token));
    $user_data = $user_query->fetch();
    $id_utilisateur = $user_data['id_utilisateur'];

    // Vérifier que l'annonce appartient à l'utilisateur connecté
    $check_query = $bdd->prepare('SELECT * FROM annonces WHERE id_annonce = ? AND id_utilisateur = ?');
    $check_query->execute(array($id_annonce, $id_utilisateur));
    $annonce = $check_query->fetch();

    if ($annonce) {
        // Mettre à jour l'annonce comme vendue
        $update_query = $bdd->prepare('UPDATE annonces SET vendu = 1 WHERE id_annonce = ?');
        $update_query->execute(array($id_annonce));
        echo "Annonce marquée comme vendue.";
    } else {
        echo "Erreur : Vous n'avez pas la permission de modifier cette annonce.";
    }
} else {
    echo "Erreur : Paramètres manquants.";
}
?>
