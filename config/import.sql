-- Adminer 4.7.5 MySQL dump


SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `blog` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `blog`;

DROP TABLE IF EXISTS `Article`;
CREATE TABLE `Article` (
  `idArticle` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(70) DEFAULT NULL,
  `content` longtext,
  `date` datetime DEFAULT NULL,
  `chapo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `dateUpdate` datetime DEFAULT NULL,
  `authorId` tinyint(3) unsigned DEFAULT NULL,
  `validated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`idArticle`),
  UNIQUE KEY `idArticle_UNIQUE` (`idArticle`),
  KEY `fk_authorIdx` (`authorId`),
  CONSTRAINT `fk_author` FOREIGN KEY (`authorId`) REFERENCES `User` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Article` (`idArticle`, `title`, `content`, `date`, `chapo`, `dateUpdate`, `authorId`, `validated`) VALUES
(1,	'Premier article modifié',	'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. \r\n\r\nVive le couscous et les oeufs',	'2019-12-11 21:45:33',	'Carrément1234',	'2020-01-09 01:51:21',	1,	1),
(2,	'Second article',	'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ',	'2019-12-12 21:00:45',	'Chapo ajouté encore une fois',	'2019-12-20 15:42:01',	1,	1),
(3,	'Troisieme article',	'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ',	'2019-12-12 22:47:12',	'Interloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.',	'2019-12-20 15:42:11',	2,	1);

DROP TABLE IF EXISTS `Comment`;
CREATE TABLE `Comment` (
  `idComment` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `idArticle` tinyint(3) unsigned DEFAULT NULL,
  `content` longtext,
  `date` datetime DEFAULT NULL,
  `idUser` tinyint(3) unsigned DEFAULT NULL,
  `validate` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`idComment`),
  UNIQUE KEY `idComment_UNIQUE` (`idComment`),
  KEY `fk_comment_article_idx` (`idArticle`),
  KEY `fk_comment_authorIdx` (`idUser`),
  CONSTRAINT `fk_comment_article` FOREIGN KEY (`idArticle`) REFERENCES `Article` (`idArticle`),
  CONSTRAINT `fk_comment_author` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Comment` (`idComment`, `idArticle`, `content`, `date`, `idUser`, `validate`) VALUES
(1,	1,	'Super Article',	'2019-12-12 01:05:06',	2,	1),
(2,	2,	'Article émouvant',	'2019-12-12 02:06:07',	2,	1),
(3,	3,	'Mauvais Article',	'2019-12-12 03:04:08',	1,	1),
(4,	1,	'blablablablabla',	'2019-12-13 02:05:06',	1,	1);

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `idUser` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(70) DEFAULT NULL,
  `dateRegister` datetime DEFAULT NULL,
  `rank` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '2',
  `forgotToken` varchar(90) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `forgotTokenExpiration` datetime DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `idUser_UNIQUE` (`idUser`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `pseudo_UNIQUE` (`pseudo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `User` (`idUser`, `pseudo`, `email`, `password`, `dateRegister`, `rank`, `forgotToken`, `forgotTokenExpiration`) VALUES
(1,	'SuperAdmin',	'admin@admin.com',	'$2y$10$/hMiIISnBgvV0PlftWFeB.TAIZSNOKV7LQozKeDUm1EPvRVYBP0UK',	'2000-01-01 00:00:00',	'1',	'1234',	'2020-01-30 05:46:22'),
(2,	'Théo',	'theo@gmail.com',	'$2y$10$/hMiIISnBgvV0PlftWFeB.TAIZSNOKV7LQozKeDUm1EPvRVYBP0UK',	'2019-12-12 12:00:00',	'2',	NULL,	NULL),
(4,	'user',	'user@user.com',	'$2y$10$5Ng1iq/F31LyKu5j.Oib8u0EQi.bN7ujDvA4oe2EwBC2Ymc/2MTvO',	'2019-12-24 17:20:19',	'2',	NULL,	NULL);

-- 2020-01-20 05:21:46