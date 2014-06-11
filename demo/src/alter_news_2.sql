CREATE TABLE IF NOT EXISTS `news_editors` (
  `article_id` INT UNSIGNED NOT NULL,
  `editor_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`article_id`, `editor_id`),
  INDEX `fk_news_editors_editors_idx` (`editor_id` ASC),
  INDEX `fk_news_editors_articles_idx` (`article_id` ASC),
  CONSTRAINT `fk_news_editors_articles_idx`
    FOREIGN KEY (`article_id`)
    REFERENCES `news` (`article_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_news_editors_editors_idx`
    FOREIGN KEY (`editor_id`)
    REFERENCES `writers` (`writer_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

insert into news_editors(article_id, editor_id) select article_id, writer_id from news_writers;
drop table news_writers;