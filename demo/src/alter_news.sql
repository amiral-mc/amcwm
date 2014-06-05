
CREATE TABLE IF NOT EXISTS `news_writers` (
  `article_id` INT UNSIGNED NOT NULL,
  `writer_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`article_id`, `writer_id`),
  INDEX `fk_news_has_writers_writers1_idx` (`writer_id` ASC),
  INDEX `fk_news_has_writers_news1_idx` (`article_id` ASC),
  CONSTRAINT `fk_news_writers_news1`
    FOREIGN KEY (`article_id`)
    REFERENCES `news` (`article_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_news_writers_writers1`
    FOREIGN KEY (`writer_id`)
    REFERENCES `writers` (`writer_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS `news_sources` (
  `source_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content_lang` CHAR(2) NOT NULL,
  `source` VARCHAR(100) NOT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`source_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `news_sources_translation` (
  `source_id` SMALLINT UNSIGNED NOT NULL,
  `content_lang` CHAR(2) NOT NULL,
  `source` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`source_id`, `content_lang`),
  INDEX `fk_news_source_translation_news_source1_idx` (`source_id` ASC),
  CONSTRAINT `fk_news_source_translation_news_source1`
    FOREIGN KEY (`source_id`)
    REFERENCES `news_sources` (`source_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

alter table news add source_id SMALLINT UNSIGNED NULL DEFAULT NULL,
add INDEX `fk_news_news_source1_idx` (`source_id` ASC),
add CONSTRAINT `fk_news_news_source1` FOREIGN KEY (`source_id`) REFERENCES `news_sources` (`source_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

insert into news_sources(`source`, content_lang) select distinct `source` , content_lang from news_translation where `source` !=  '';
insert into news_sources_translation(`source_id`, `source`, content_lang) select distinct `source_id`, `source` , content_lang from news_sources;
alter table news_sources drop content_lang, drop `source`;

update news, news_translation set source_id = (select source_id from news_sources_translation where news_sources_translation.source = news_translation.source)
where news.article_id = news_translation.article_id;


-- select n.article_id, n.source_id, nt.source , nt.content_lang,  st.source, st.content_lang from news n 
-- inner join news_translation nt on n.article_id = nt.article_id
-- inner join news_sources s on n.source_id = s.source_id 
-- inner join news_sources_translation st on s.source_id = st.source_id and st.content_lang = nt.content_lang;

drop table `news_translation`;

update modules set enabled = 1 where `module` = 'writers';