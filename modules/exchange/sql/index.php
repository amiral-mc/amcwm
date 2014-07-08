<?php
'CREATE  TABLE IF NOT EXISTS `exchange` (
  `exchange_id` INT NOT NULL AUTO_INCREMENT ,
  `exchange_name` VARCHAR(45) NOT NULL ,
  `currency` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`exchange_id`) )
ENGINE = InnoDB

CREATE  TABLE IF NOT EXISTS `exchange_companies` (
  `company_id` INT NOT NULL AUTO_INCREMENT ,
  `company_name` VARCHAR(45) NOT NULL ,
  `published` TINYINT NOT NULL ,
  `company_code` VARCHAR(45) NULL ,
  `exchange_id` INT NOT NULL ,
  PRIMARY KEY (`company_id`) ,
  INDEX `fk_exchange_companies_exchange1` (`exchange_id` ASC) ,
  CONSTRAINT `fk_exchange_companies_exchange1`
    FOREIGN KEY (`exchange_id` )
    REFERENCES `exchange` (`exchange_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB

CREATE  TABLE IF NOT EXISTS `exchange_trading` (
  `exchange_trading_id` INT NOT NULL AUTO_INCREMENT ,
  `trading_date` DATE NOT NULL ,
  `index` DECIMAL(12,2) NOT NULL ,
  `percentage` DECIMAL(12,10) NOT NULL ,
  `net` DECIMAL(12,2) NOT NULL ,
  `exchange_id` INT NOT NULL ,
  PRIMARY KEY (`exchange_trading_id`) ,
  INDEX `fk_exchange_trading_exchange1` (`exchange_id` ASC) ,
  CONSTRAINT `fk_exchange_trading_exchange1`
    FOREIGN KEY (`exchange_id` )
    REFERENCES `exchange` (`exchange_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB';