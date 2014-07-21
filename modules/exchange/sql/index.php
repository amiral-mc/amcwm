<?php
'CREATE TABLE IF NOT EXISTS `exchange` (
  `exchange_id` INT NOT NULL AUTO_INCREMENT,
  `exchange_name` VARCHAR(45) NULL,
  `currency` VARCHAR(45) NULL,
  PRIMARY KEY (`exchange_id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `exchange_companies` (
  `exchange_companies_id` INT NOT NULL AUTO_INCREMENT,
  `exchange_id` INT NOT NULL,
  `code` VARCHAR(45) NULL,
  `published` TINYINT(1) NOT NULL,
  PRIMARY KEY (`exchange_companies_id`),
  INDEX `fk_exchange_companies_exchange1_idx` (`exchange_id` ASC),
  CONSTRAINT `fk_exchange_companies_exchange1`
    FOREIGN KEY (`exchange_id`)
    REFERENCES `exchange` (`exchange_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `exchange_trading` (
  `exchange_id` INT NOT NULL,
  `exchange_date` DATE NOT NULL,
  `trading_value` DECIMAL(16,2) NOT NULL,
  `shares_of_stock` DECIMAL(16,2) NOT NULL,
  `closing_value` DECIMAL(12,2) NOT NULL,
  `difference_value` DECIMAL(8,2) NOT NULL,
  `difference_percentage` DECIMAL(8,2) NOT NULL,
  PRIMARY KEY (`exchange_id`, `exchange_date`),
  INDEX `fk_exchange_trading_exchange1_idx` (`exchange_id` ASC),
  CONSTRAINT `fk_exchange_trading_exchange1`
    FOREIGN KEY (`exchange_id`)
    REFERENCES `exchange` (`exchange_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `exchange_trading_companies` (
  `exchange_trading_exchange_id` INT NOT NULL,
  `exchange_trading_exchange_date` DATE NOT NULL,
  `exchange_companies_exchange_companies_id` INT NOT NULL,
  `opening_value` DECIMAL(12,2) NULL,
  `closing_value` DECIMAL(12,2) NULL,
  `difference_percentage` DECIMAL(8,2) NULL,
  PRIMARY KEY (`exchange_trading_exchange_id`, `exchange_trading_exchange_date`, `exchange_companies_exchange_companies_id`),
  INDEX `fk_exchange_trading_has_exchange_companies_exchange_compani_idx` (`exchange_companies_exchange_companies_id` ASC),
  INDEX `fk_exchange_trading_has_exchange_companies_exchange_trading_idx` (`exchange_trading_exchange_id` ASC, `exchange_trading_exchange_date` ASC),
  CONSTRAINT `fk_exchange_trading_has_exchange_companies_exchange_trading1`
    FOREIGN KEY (`exchange_trading_exchange_id` , `exchange_trading_exchange_date`)
    REFERENCES `exchange_trading` (`exchange_id` , `exchange_date`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exchange_trading_has_exchange_companies_exchange_companies1`
    FOREIGN KEY (`exchange_companies_exchange_companies_id`)
    REFERENCES `exchange_companies` (`exchange_companies_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `exchange_companies_translation` (
  `exchange_companies_id` INT NOT NULL,
  `company_name` VARCHAR(100) NOT NULL,
  `content_lang` CHAR(2) NOT NULL,
  PRIMARY KEY (`exchange_companies_id`),
  CONSTRAINT `fk_exchange_companies_translation_exchange_companies1`
    FOREIGN KEY (`exchange_companies_id`)
    REFERENCES `exchange_companies` (`exchange_companies_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB';