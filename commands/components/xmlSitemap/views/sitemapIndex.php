<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?> 
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">  
    <?php foreach ($linksItems as $item): ?>
        <sitemap>
              <loc><?php echo Yii::app()->params['siteUrl'] . "/xmlsitemap/{$item['file_name']}"; ?></loc> 
              <lastmod><?php echo Yii::app()->dateFormatter->format("yyyy-MM-dd:THH:mm:ss+02:00", $item['created_date']); ?></lastmod> 
        </sitemap>
    <?php endforeach; ?>
</sitemapindex>