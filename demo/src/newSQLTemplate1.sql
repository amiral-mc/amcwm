SELECT `t`.`article_id`, `t`.`hits`, `t`.`thumb`, `tt`.`article_header`, `create_date`, `publish_date`
FROM articles t force index (articles_create_date_idx)
inner join articles_translation tt on t.article_id = tt.article_id inner JOIN news ON t.article_id = news.article_id 
WHERE tt.content_lang = 'en'
         and t.publish_date <= '2014-07-20 12:01:28'            
         and (t.expire_date  >= '2014-07-20 12:01:28' or t.expire_date is null)  
         and t.published = 1  and  (t.in_list = 1) and t.parent_article is null and tt.article_detail is not null and t.publish_date >= '2014-07-20 11:00:00' and t.publish_date <='2014-07-20 11:59:59' and (t.archive = 0 or t.archive is null)
ORDER BY `article_sort` ASC LIMIT 1


update articles inner join news on news.article_id = articles.article_id set create_date = now() - interval 1 hour, publish_date = now() where articles.article_id < 600;

update articles inner join news on news.article_id = articles.article_id set publish_date = now() - interval 1 hour, create_date = now() where articles.article_id < 600;

select publish_date, create_date from articles t where t.publish_date >= '2014-07-20 11:00:00' and t.publish_date <='2014-07-20 11:59:59'
