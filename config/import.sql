-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 05 juil. 2023 à 20:48
-- Version du serveur : 10.4.19-MariaDB
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `idArticle` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(70) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `chapo` text DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  `authorId` tinyint(3) UNSIGNED DEFAULT NULL,
  `validated` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`idArticle`),
  UNIQUE KEY `idPost_UNIQUE` (`idArticle`),
  KEY `authorId` (`authorId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`idArticle`, `title`, `content`, `date`, `chapo`, `dateUpdate`, `authorId`, `validated`) VALUES
(1, 'Compétences', 'Lors de ma formation j’ai pu développer mes compétences en HTML5 / CSS3 / JAVASCRIPT / GIT / NODE.JS / REACT / MySql / MongoDB / Bootstrap / Seo / SASS / API REST / PHP / Symfony / Wordpress\r\n', '2023-07-05 11:45:57', 'Openclasssrooms', NULL, 1, 0),
(2, 'bonjour', 'bonjourbonjourbonjour', '2023-07-05 21:25:31', 'bonjourbonjourbonjour', NULL, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `idComment` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idPost` tinyint(3) UNSIGNED DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `idUser` tinyint(3) UNSIGNED DEFAULT NULL,
  `validate` tinyint(3) UNSIGNED DEFAULT 0,
  PRIMARY KEY (`idComment`),
  UNIQUE KEY `idComment_UNIQUE` (`idComment`),
  KEY `fk_comment_article_idx` (`idPost`),
  KEY `fk_comment_authorIdx` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(40) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `password` varchar(70) DEFAULT NULL,
  `dateRegister` datetime DEFAULT NULL,
  `forgotToken` varchar(90) DEFAULT NULL,
  `forgotTokenExpiration` datetime DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `idUser_UNIQUE` (`idUser`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `pseudo_UNIQUE` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `pseudo`, `email`, `password`, `dateRegister`, `forgotToken`, `forgotTokenExpiration`) VALUES
(1, 'Hamdaoui Rogaya', 'hamdaouirogoya1994@outlook.fr', 'Bismeallah1994', NULL, NULL, NULL),
(2, 'Chaimoutaa', 'chaimaHamdaoui@hotmail.fr', '$2y$10$fXCcb6ZaFJOCyJqtmY6J4u3Jj0FclhIw5V99CV.nEUEpU8ewmj.Pq', '2023-07-05 18:48:59', NULL, NULL),
(3, 'Sophie', 'sophie@hotmail.fr', '$2y$10$x9wSW9noYs1eVIempB0xS.GYVMtt1zCllf3P68CBBsveW1fpFfPom', '2023-07-05 20:13:13', NULL, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`authorId`) REFERENCES `user` (`idUser`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`idPost`) REFERENCES `article` (`idArticle`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
