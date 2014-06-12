alter table writers drop index writer_type_UNIQUE;
ALTER TABLE `users_log` drop FOREIGN KEY `fk_users_log_user_actions`;
ALTER TABLE `users_log` ADD CONSTRAINT `fk_users_log_user_actions` FOREIGN KEY (`action_id`) REFERENCES `actions` (`action_id`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE  `articles_translation` CHANGE  `article_detail`  `article_detail` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
alter table writers change `writer_type` `writer_type` TINYINT(3) UNSIGNED NOT NULL DEFAULT 1;
update writers set  `writer_type`  = 1;

CREATE TABLE IF NOT EXISTS `essays` (
  `article_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`article_id`),
  CONSTRAINT `fk_news_articles0`
    FOREIGN KEY (`article_id`)
    REFERENCES `articles` (`article_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;