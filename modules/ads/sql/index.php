<?php
'CREATE TABLE IF NOT EXISTS `ads_servers_config` (
  `server_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `header_code` TEXT NULL default NULL,
  `server_name` VARCHAR(35) NOT NULL,
  PRIMARY KEY (`server_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `default_ads_zones` (
  `zone_id` TINYINT UNSIGNED NOT NULL,
  `zone_name` VARCHAR(100) NOT NULL,
  `width` SMALLINT UNSIGNED NOT NULL,
  `height` SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (`zone_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `ads_zones` (
  `ad_id` SMALLINT NOT NULL AUTO_INCREMENT,
  `server_id` SMALLINT UNSIGNED NOT NULL,
  `zone_id` TINYINT UNSIGNED NOT NULL,
  `invocation_code` TEXT NOT NULL,
  `published` tinyint(1) NOT NULL,
  INDEX `fk_ads_zones_ads_servers_config1_idx` (`server_id` ASC),
  PRIMARY KEY (`ad_id`),
  INDEX `fk_ads_zones_default_ads_zones1_idx` (`zone_id` ASC),
  CONSTRAINT `fk_ads_zones_ads_servers_config1`
    FOREIGN KEY (`server_id`)
    REFERENCES `ads_servers_config` (`server_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ads_zones_default_ads_zones1`
    FOREIGN KEY (`zone_id`)
    REFERENCES `default_ads_zones` (`zone_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `ads_zones_has_sections` (
  `ad_id` SMALLINT NOT NULL,
  `section_id` SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (`ad_id`, `section_id`),
  INDEX `fk_ads_zones_has_sections_sections1_idx` (`section_id` ASC),
  INDEX `fk_ads_zones_has_sections_ads_zones1_idx` (`ad_id` ASC),
  CONSTRAINT `fk_ads_zones_sections_ads_zones1`
    FOREIGN KEY (`ad_id`)
    REFERENCES `ads_zones` (`ad_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ads_zones_sections_sections1`
    FOREIGN KEY (`section_id`)
    REFERENCES `sections` (`section_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;';