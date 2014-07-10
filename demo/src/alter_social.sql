CREATE TABLE IF NOT EXISTS `module_social_config` (
  `config_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` MEDIUMINT NOT NULL,
  `social_id` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  `ref_id` INT UNSIGNED NULL,
  `table_id` TINYINT UNSIGNED NULL,
  `post_date` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`config_id`),
  INDEX `fk_module_attachment_modules1` (`module_id` ASC),
  INDEX `fk_module_social_config_social_networks1_idx` (`social_id` ASC),
  CONSTRAINT `fk_module_social`
    FOREIGN KEY (`module_id`)
    REFERENCES `modules` (`module_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_module_social_config_social_networks1`
    FOREIGN KEY (`social_id`)
    REFERENCES `social_networks` (`social_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `module_social_config_langs` (
  `config_id` INT UNSIGNED NOT NULL,
  `content_lang` CHAR(2) NOT NULL,
  PRIMARY KEY (`config_id`, `content_lang`),
  INDEX `fk_module_social_config_langs_module_social_config1_idx` (`config_id` ASC),
  CONSTRAINT `fk_module_social_config_langs_module_social_config1`
    FOREIGN KEY (`config_id`)
    REFERENCES `module_social_config` (`config_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;