<?php

"CREATE TABLE IF NOT EXISTS `products` (
  `product_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `gallery_id` INT UNSIGNED NOT NULL,
  `section_id` SMALLINT UNSIGNED NOT NULL,
  `votes` INT UNSIGNED NULL DEFAULT '0',
  `votes_rate` DOUBLE NULL DEFAULT '1',
  `hits` INT UNSIGNED NULL DEFAULT '0',
  `shared` INT UNSIGNED NULL DEFAULT 0,
  `comments` INT UNSIGNED NULL DEFAULT 0,
  `published` TINYINT UNSIGNED NULL DEFAULT '1',
  `create_date` DATETIME NOT NULL,
  `publish_date` DATETIME NOT NULL,
  `expire_date` DATETIME NULL DEFAULT NULL,
  `update_date` DATETIME NULL DEFAULT NULL,
  `product_sort` INT UNSIGNED NULL DEFAULT 0,
  `is_system` TINYINT(1) NOT NULL DEFAULT 0,
  `price` DECIMAL(8,2) NULL DEFAULT 0,
  `product_code` VARCHAR(50) NULL,
  INDEX `articles_create_date_idx` (`create_date` DESC),
  INDEX `articles_hits_idx` (`hits` DESC),
  PRIMARY KEY (`product_id`),
  INDEX `fk_articles_sections1_idx` (`section_id` ASC),
  INDEX `fk_products_galleries1_idx` (`gallery_id` ASC),
  UNIQUE INDEX `product_code_UNIQUE` (`product_code` ASC),
  CONSTRAINT `fk_products_sections`
    FOREIGN KEY (`section_id`)
    REFERENCES `sections` (`section_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_products_galleries1`
    FOREIGN KEY (`gallery_id`)
    REFERENCES `galleries` (`gallery_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `products_translation` (
  `product_id` INT UNSIGNED NOT NULL,
  `product_name` VARCHAR(100) NOT NULL,
  `content_lang` CHAR(2) NOT NULL,
  `product_brief` TEXT NULL DEFAULT NULL,
  `product_description` VARCHAR(1024) NOT NULL,
  `product_specifications` TEXT NULL,
  `tags` VARCHAR(1024) NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`, `content_lang`),
  INDEX `fk_products_translation_products1_idx` (`product_id` ASC),
  CONSTRAINT `fk_products_translation_products1`
    FOREIGN KEY (`product_id`)
    REFERENCES `products` (`product_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 32
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `products_comments` (
  `product_comment_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`product_comment_id`),
  INDEX `fk_products_comments_products1_idx` (`product_id` ASC),
  CONSTRAINT `products_comments_fk`
    FOREIGN KEY (`product_comment_id`)
    REFERENCES `comments` (`comment_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_products_comments_products1`
    FOREIGN KEY (`product_id`)
    REFERENCES `products` (`product_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;";
