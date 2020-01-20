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
  `id_article` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(70) DEFAULT NULL,
  `content` longtext,
  `date` datetime DEFAULT NULL,
  `chapo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `date_update` datetime DEFAULT NULL,
  `author_id` tinyint(3) unsigned DEFAULT NULL,
  `validated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_article`),
  UNIQUE KEY `id_article_UNIQUE` (`id_article`),
  KEY `fk_author_idx` (`author_id`),
  CONSTRAINT `fk_author` FOREIGN KEY (`author_id`) REFERENCES `User` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Article` (`id_article`, `title`, `content`, `date`, `chapo`, `date_update`, `author_id`, `validated`) VALUES
(1,	'Premier article modifié',	'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. \r\n\r\nVive le couscous et les oeufs',	'2019-12-11 21:45:33',	'Carrément1234',	'2020-01-09 01:51:21',	1,	1),
(2,	'Second article',	'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ',	'2019-12-12 21:00:45',	'Chapo ajouté encore une fois',	'2019-12-20 15:42:01',	1,	1),
(3,	'Troisieme article',	'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ',	'2019-12-12 22:47:12',	'Interloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.',	'2019-12-20 15:42:11',	2,	1);

DROP TABLE IF EXISTS `Comment`;
CREATE TABLE `Comment` (
  `id_comment` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_article` tinyint(3) unsigned DEFAULT NULL,
  `content` longtext,
  `date` datetime DEFAULT NULL,
  `id_user` tinyint(3) unsigned DEFAULT NULL,
  `validate` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id_comment`),
  UNIQUE KEY `id_comment_UNIQUE` (`id_comment`),
  KEY `fk_comment_article_idx` (`id_article`),
  KEY `fk_comment_author_idx` (`id_user`),
  CONSTRAINT `fk_comment_article` FOREIGN KEY (`id_article`) REFERENCES `Article` (`id_article`),
  CONSTRAINT `fk_comment_author` FOREIGN KEY (`id_user`) REFERENCES `User` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Comment` (`id_comment`, `id_article`, `content`, `date`, `id_user`, `validate`) VALUES
(1,	1,	'Super Article',	'2019-12-12 01:05:06',	2,	1),
(2,	2,	'Article émouvant',	'2019-12-12 02:06:07',	2,	1),
(3,	3,	'Mauvais Article',	'2019-12-12 03:04:08',	1,	1),
(4,	1,	'blablablablabla',	'2019-12-13 02:05:06',	1,	1);

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `id_user` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(70) DEFAULT NULL,
  `date_register` datetime DEFAULT NULL,
  `rank` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '2',
  `forgot_token` varchar(90) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `forgot_token_expiration` datetime DEFAULT NULL,
  `auth_token` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `auth_token_expiration` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `id_user_UNIQUE` (`id_user`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `pseudo_UNIQUE` (`pseudo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `User` (`id_user`, `pseudo`, `email`, `password`, `date_register`, `rank`, `forgot_token`, `forgot_token_expiration`, `auth_token`, `auth_token_expiration`) VALUES
(1,	'SuperAdmin',	'admin@admin.com',	'$2y$10$/hMiIISnBgvV0PlftWFeB.TAIZSNOKV7LQozKeDUm1EPvRVYBP0UK',	'2000-01-01 00:00:00',	'1',	'1234',	'2020-01-30 05:46:22',	'093be4d672767f384070ce32728f1748cfcfccae14d4d720f7e7dcba8155b4d1',	'2020-01-27 03:59:54'),
(2,	'Théo',	'theo@gmail.com',	'$2y$10$/hMiIISnBgvV0PlftWFeB.TAIZSNOKV7LQozKeDUm1EPvRVYBP0UK',	'2019-12-12 12:00:00',	'2',	NULL,	NULL,	'1',	NULL),
(4,	'user',	'user@user.com',	'$2y$10$5Ng1iq/F31LyKu5j.Oib8u0EQi.bN7ujDvA4oe2EwBC2Ymc/2MTvO',	'2019-12-24 17:20:19',	'2',	NULL,	NULL,	NULL,	NULL);

-- 2020-01-20 05:21:46