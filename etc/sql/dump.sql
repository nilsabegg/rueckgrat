SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `default_schema` ;
USE `default_schema` ;

-- -----------------------------------------------------
-- Table `default_schema`.`action`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`action` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`action` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL DEFAULT '' ,
  `credits` INT(3) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`sector`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`sector` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`sector` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`company`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`company` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`company` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL DEFAULT '' ,
  `domain` VARCHAR(255) NOT NULL DEFAULT '' ,
  `top_level_domain` VARCHAR(255) NOT NULL DEFAULT '' ,
  `subdomain` VARCHAR(255) NOT NULL DEFAULT '' ,
  `website` VARCHAR(255) NULL DEFAULT NULL ,
  `logo` VARCHAR(20) NULL DEFAULT NULL ,
  `sector_id` INT(20) UNSIGNED NULL DEFAULT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) ,
  INDEX `sector_id` (`sector_id` ASC) ,
  CONSTRAINT `company_ibfk_1`
    FOREIGN KEY (`sector_id` )
    REFERENCES `default_schema`.`sector` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`position`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`position` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`position` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`user` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`user` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NOT NULL DEFAULT '' ,
  `email_private` VARCHAR(255) NULL DEFAULT NULL ,
  `firstname` VARCHAR(255) NULL DEFAULT NULL ,
  `lastname` VARCHAR(255) NULL DEFAULT NULL ,
  `position_id` INT(20) UNSIGNED NULL DEFAULT NULL ,
  `password` VARCHAR(255) NOT NULL DEFAULT '' ,
  `salt` VARCHAR(255) NOT NULL DEFAULT '' ,
  `avatar` CHAR(1) NULL DEFAULT NULL ,
  `is_active` TINYINT(1) NOT NULL DEFAULT '0' ,
  `is_admin` TINYINT(1) NULL DEFAULT NULL ,
  `company_id` INT(20) UNSIGNED NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email` (`email` ASC) ,
  UNIQUE INDEX `email_private` (`email_private` ASC) ,
  INDEX `position_id` (`position_id` ASC) ,
  INDEX `company_id` (`company_id` ASC) ,
  CONSTRAINT `user_ibfk_2`
    FOREIGN KEY (`company_id` )
    REFERENCES `default_schema`.`company` (`id` ),
  CONSTRAINT `user_ibfk_1`
    FOREIGN KEY (`position_id` )
    REFERENCES `default_schema`.`position` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`idea`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`idea` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`idea` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `text` TEXT NOT NULL ,
  `user_id` INT(20) UNSIGNED NOT NULL ,
  `is_deleted` TINYINT(1) NOT NULL DEFAULT '0' ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `idea_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `default_schema`.`user` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`idea_comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`idea_comment` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`idea_comment` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idea_id` INT(20) UNSIGNED NOT NULL ,
  `user_id` INT(20) UNSIGNED NOT NULL ,
  `text` TEXT NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idea_id` (`idea_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `idea_comment_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `default_schema`.`user` (`id` ),
  CONSTRAINT `idea_comment_ibfk_1`
    FOREIGN KEY (`idea_id` )
    REFERENCES `default_schema`.`idea` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`idea_vote`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`idea_vote` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`idea_vote` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idea_id` INT(20) UNSIGNED NOT NULL ,
  `user_id` INT(20) UNSIGNED NOT NULL ,
  `vote` TINYINT(1) NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `idea_id` (`idea_id` ASC, `user_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `idea_vote_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `default_schema`.`user` (`id` ),
  CONSTRAINT `idea_vote_ibfk_1`
    FOREIGN KEY (`idea_id` )
    REFERENCES `default_schema`.`idea` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`invite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`invite` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`invite` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(20) UNSIGNED NOT NULL ,
  `email` VARCHAR(255) NOT NULL DEFAULT '' ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `invite_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `default_schema`.`user` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `default_schema`.`user_action`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `default_schema`.`user_action` ;

CREATE  TABLE IF NOT EXISTS `default_schema`.`user_action` (
  `id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(20) UNSIGNED NOT NULL ,
  `action_id` INT(20) UNSIGNED NOT NULL ,
  `idea_id` INT(20) UNSIGNED NULL DEFAULT NULL ,
  `idea_comment_id` INT(20) UNSIGNED NULL DEFAULT NULL ,
  `idea_vote_id` INT(20) UNSIGNED NULL DEFAULT NULL ,
  `invite_id` INT(20) UNSIGNED NULL DEFAULT NULL ,
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `action_id` (`action_id` ASC) ,
  INDEX `idea_id` (`idea_id` ASC) ,
  INDEX `idea_comment_id` (`idea_comment_id` ASC) ,
  INDEX `idea_vote_id` (`idea_vote_id` ASC) ,
  INDEX `invite_id` (`invite_id` ASC) ,
  CONSTRAINT `user_action_ibfk_6`
    FOREIGN KEY (`invite_id` )
    REFERENCES `default_schema`.`invite` (`id` ),
  CONSTRAINT `user_action_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `default_schema`.`user` (`id` ),
  CONSTRAINT `user_action_ibfk_2`
    FOREIGN KEY (`action_id` )
    REFERENCES `default_schema`.`action` (`id` ),
  CONSTRAINT `user_action_ibfk_3`
    FOREIGN KEY (`idea_id` )
    REFERENCES `default_schema`.`idea` (`id` ),
  CONSTRAINT `user_action_ibfk_4`
    FOREIGN KEY (`idea_comment_id` )
    REFERENCES `default_schema`.`idea_comment` (`id` ),
  CONSTRAINT `user_action_ibfk_5`
    FOREIGN KEY (`idea_vote_id` )
    REFERENCES `default_schema`.`idea_vote` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
