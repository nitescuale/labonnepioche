-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 24 avr. 2024 à 19:26
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `labonnepioche`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE IF NOT EXISTS `annonces` (
  `id_annonce` bigint NOT NULL AUTO_INCREMENT,
  `id_utilisateur` bigint NOT NULL,
  `titre` text NOT NULL,
  `description` text NOT NULL,
  `prix` float NOT NULL,
  `categorie` text NOT NULL,
  `etat` text NOT NULL,
  `date_publication` date NOT NULL,
  `vendu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_annonce`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id_annonce`, `id_utilisateur`, `titre`, `description`, `prix`, `categorie`, `etat`, `date_publication`, `vendu`) VALUES
(10, 6, 'Lego', 'Lego', 25, 'Loisirs', 'Très bon état', '2024-04-22', 0),
(11, 6, 'Voiture', 'Alpine A310 comme neuve', 65000, 'Véhicules', 'Neuf', '2024-04-23', 0);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'Autres'),
(2, 'Électronique'),
(3, 'Emploi'),
(4, 'Famille'),
(5, 'Immobilier'),
(6, 'Location de vacances'),
(7, 'Loisirs'),
(8, 'Maison & Jardin'),
(9, 'Mode'),
(10, 'Véhicules');

-- --------------------------------------------------------

--
-- Structure de la table `etats_produit`
--

DROP TABLE IF EXISTS `etats_produit`;
CREATE TABLE IF NOT EXISTS `etats_produit` (
  `id_etat` int NOT NULL AUTO_INCREMENT,
  `nom_etat` text NOT NULL,
  PRIMARY KEY (`id_etat`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `etats_produit`
--

INSERT INTO `etats_produit` (`id_etat`, `nom_etat`) VALUES
(1, 'Neuf'),
(2, 'Très bon état'),
(3, 'Bon état'),
(4, 'État satisfaisant'),
(5, 'État correct');

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `id_favori` int NOT NULL AUTO_INCREMENT,
  `id_annonce` bigint NOT NULL,
  `id_utilisateur` bigint NOT NULL,
  PRIMARY KEY (`id_favori`),
  KEY `id_annonce` (`id_annonce`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `photos_annonces`
--

DROP TABLE IF EXISTS `photos_annonces`;
CREATE TABLE IF NOT EXISTS `photos_annonces` (
  `id_photo` bigint NOT NULL AUTO_INCREMENT,
  `id_annonce` bigint NOT NULL,
  `url_photo` text NOT NULL,
  PRIMARY KEY (`id_photo`),
  KEY `id_annonce` (`id_annonce`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `photos_annonces`
--

INSERT INTO `photos_annonces` (`id_photo`, `id_annonce`, `url_photo`) VALUES
(1, 10, 'http://localhost/labonnepioche/ad_pics/f58e48cc-73bc-48c5-98d0-0416fcae285c.jpeg'),
(2, 10, 'http://localhost/labonnepioche/ad_pics/20e3aaa7-d0c2-461d-92cb-49443a0e8c2c.jpeg'),
(3, 10, 'http://localhost/labonnepioche/ad_pics/16c57286-5a4c-40ff-93d7-29a94b3fd487.jpeg'),
(4, 11, 'http://localhost/labonnepioche/ad_pics/alpine-a310-v6-1977.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id_review` bigint NOT NULL AUTO_INCREMENT,
  `id_annonce` bigint NOT NULL,
  `id_utilisateur` bigint NOT NULL,
  `note` int NOT NULL,
  `commentaire` text NOT NULL,
  `date_review` date NOT NULL,
  PRIMARY KEY (`id_review`),
  KEY `id_annonce` (`id_annonce`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id_transaction` bigint NOT NULL AUTO_INCREMENT,
  `id_annonce` bigint NOT NULL,
  `id_vendeur` bigint NOT NULL,
  `id_acheteur` bigint NOT NULL,
  `montant` float NOT NULL,
  `date_transaction` date NOT NULL,
  PRIMARY KEY (`id_transaction`),
  KEY `id_annonce` (`id_annonce`),
  KEY `id_vendeur` (`id_vendeur`),
  KEY `id_acheteur` (`id_acheteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` bigint NOT NULL AUTO_INCREMENT,
  `nom` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `prenom` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ip` varchar(20) NOT NULL,
  `token` text NOT NULL,
  `url_photo_profil` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_inscription` date NOT NULL,
  `ville` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pays` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `password`, `ip`, `token`, `url_photo_profil`, `date_inscription`, `ville`, `pays`, `admin`) VALUES
(6, 'Nitescu', 'Alexandru', 'nitescuale@cy-tech.fr', 'a5ce47bc20c89b74dd3e06ffa403ed608a994aa2d95a6d169698bba8356d3936', '::1', '2147483647', 'http://localhost/labonnepioche/pfps/user_icon.png', '2024-04-14', '', '', 0),
(12, 'test', 'test', 'test@test.fr', '2096676b2c0ba76e4fd51429c9c5009d3339ba68a1edb32cdc0e3c07a09b17c6', '::1', '1', 'http://localhost/labonnepioche/pfps/9e8ec3c525194846c3b360a6095d5f37.jpg', '2024-04-18', '', '', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonces` (`id_annonce`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `annonces` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `photos_annonces`
--
ALTER TABLE `photos_annonces`
  ADD CONSTRAINT `photos_annonces_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonces` (`id_annonce`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonces` (`id_annonce`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`id_annonce`) REFERENCES `annonces` (`id_annonce`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`id_vendeur`) REFERENCES `annonces` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`id_acheteur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
