/* For development purpose only ! */
DROP SCHEMA IF EXISTS `p5` ;

-- -----------------------------------------------------
-- Schema p5
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `p5` CHARACTER SET utf8;
USE `p5` ;

-- -----------------------------------------------------
-- Table `p5`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p5`.`user` ;

CREATE TABLE IF NOT EXISTS `p5`.`user` (
  `id_user` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(70) NULL,
  `pseudo` VARCHAR(40) NULL,
  `password` VARCHAR(70) NULL,
  `date_register` DATETIME NULL,
  `rank` VARCHAR(45) NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE INDEX `id_user_UNIQUE` (`id_user` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `pseudo_UNIQUE` (`pseudo` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `p5`.`article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p5`.`article` ;

CREATE TABLE IF NOT EXISTS `p5`.`article` (
  `id_article` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(70) NULL,
  `content` LONGTEXT NULL,
  `date` DATETIME NULL,
  `chapo` VARCHAR(45) NULL,
  `date_update` DATETIME NULL,
  `author_id` TINYINT UNSIGNED NULL,
  PRIMARY KEY (`id_article`),
  UNIQUE INDEX `id_article_UNIQUE` (`id_article` ASC),
  INDEX `fk_author_idx` (`author_id` ASC),
  CONSTRAINT `fk_author`
    FOREIGN KEY (`author_id`)
    REFERENCES `p5`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `p5`.`comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `p5`.`comment` ;

CREATE TABLE IF NOT EXISTS `p5`.`comment` (
  `id_comment` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_article` TINYINT UNSIGNED NULL,
  `content` LONGTEXT NULL,
  `date` DATETIME NULL,
  `date_update` DATETIME NULL,
  `id_user` TINYINT UNSIGNED NULL,
  PRIMARY KEY (`id_comment`),
  UNIQUE INDEX `id_comment_UNIQUE` (`id_comment` ASC),
  INDEX `fk_comment_article_idx` (`id_article` ASC),
  INDEX `fk_comment_author_idx` (`id_user` ASC),
  CONSTRAINT `fk_comment_article`
    FOREIGN KEY (`id_article`)
    REFERENCES `p5`.`article` (`id_article`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comment_author`
    FOREIGN KEY (`id_user`)
    REFERENCES `p5`.`user` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Data for table `p5`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `p5`;
INSERT INTO `p5`.`user` (`id_user`, `email`, `pseudo`, `password`, `date_register`, `rank`) VALUES (DEFAULT, 'admin@admin.com', 'SuperAdmin', 'password', '2000-01-01 00:00:00', '1');
INSERT INTO `p5`.`user` (`id_user`, `email`, `pseudo`, `password`, `date_register`, `rank`) VALUES (DEFAULT, 'theo@gmail.com', 'Théo', 'password', '2019-12-12 12:00:00', '2');

COMMIT;


-- -----------------------------------------------------
-- Data for table `p5`.`article`
-- -----------------------------------------------------
START TRANSACTION;
USE `p5`;
INSERT INTO `p5`.`article` (`id_article`, `title`, `content`, `date`, `chapo`, `date_update`, `author_id`) VALUES (DEFAULT, 'Premier Article', 'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\n\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\n\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ', '2019-12-11 21:45:33', NULL, NULL, 1);
INSERT INTO `p5`.`article` (`id_article`, `title`, `content`, `date`, `chapo`, `date_update`, `author_id`) VALUES (DEFAULT, 'Second Article', 'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\n\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\n\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ', '2019-12-12 21:00:45', NULL, NULL, 1);
INSERT INTO `p5`.`article` (`id_article`, `title`, `content`, `date`, `chapo`, `date_update`, `author_id`) VALUES (DEFAULT, 'Troisieme Article', 'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\n\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\n\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ', '2019-12-12 22:47:12', NULL, NULL, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `p5`.`comment`
-- -----------------------------------------------------
START TRANSACTION;
USE `p5`;
INSERT INTO `p5`.`comment` (`id_comment`, `id_article`, `content`, `date`, `date_update`, `id_user`) VALUES (DEFAULT, 1, 'Super Article', '2019-12-12 01:05:06', NULL, 2);
INSERT INTO `p5`.`comment` (`id_comment`, `id_article`, `content`, `date`, `date_update`, `id_user`) VALUES (DEFAULT, 2, 'Article émouvant', '2019-12-12 02:06:07', NULL, 2);
INSERT INTO `p5`.`comment` (`id_comment`, `id_article`, `content`, `date`, `date_update`, `id_user`) VALUES (DEFAULT, 3, 'Mauvais Article', '2019-12-12 03:04:08', NULL, 1);

COMMIT;

