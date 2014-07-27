CREATE TABLE IF NOT EXISTS `articles_log` (
  `log_id` BIGINT(20) UNSIGNED NOT NULL,
  `item_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`log_id`, `item_id`),
  INDEX `fk_articles_log_log_data1_idx` (`log_id` ASC),
  CONSTRAINT `fk_articles_log_articles1`
    FOREIGN KEY (`item_id`)
    REFERENCES `articles` (`article_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_articles_log_log_data1`
    FOREIGN KEY (`log_id`)
    REFERENCES `log_data` (`log_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;
