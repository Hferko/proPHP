-- MySQL Workbench Synchronization
-- Generated: 2022-07-22 00:46
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: hferko

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `tortak` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `tortak`.`kolkok` (
  `k_id` INT(11) NOT NULL,
  `k_nev` VARCHAR(45) NULL DEFAULT NULL,
  `eaten_eat_id` INT(11) NOT NULL,
  PRIMARY KEY (`k_id`),
  UNIQUE INDEX `k_nev_UNIQUE` (`k_nev` ASC) VISIBLE,
  INDEX `fk_kolkok_eaten1_idx` (`eaten_eat_id` ASC) VISIBLE,
  CONSTRAINT `fk_kolkok_eaten1`
    FOREIGN KEY (`eaten_eat_id`)
    REFERENCES `tortak`.`eaten` (`eat_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `tortak`.`torta` (
  `torta_id` INT(11) NOT NULL,
  `torta_nev` VARCHAR(45) NULL DEFAULT NULL,
  `eaten_eat_id` INT(11) NOT NULL,
  PRIMARY KEY (`torta_id`),
  UNIQUE INDEX `torta_nev_UNIQUE` (`torta_nev` ASC) VISIBLE,
  INDEX `fk_torta_eaten1_idx` (`eaten_eat_id` ASC) VISIBLE,
  CONSTRAINT `fk_torta_eaten1`
    FOREIGN KEY (`eaten_eat_id`)
    REFERENCES `tortak`.`eaten` (`eat_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `tortak`.`eaten` (
  `eat_id` INT(11) NOT NULL,
  `k_id` INT(11) NULL DEFAULT NULL,
  `torta_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`eat_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
