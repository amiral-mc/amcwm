<?php echo '<?xml version="1.0" encoding="utf-8" ?>' ?> 
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">  
    <?php foreach ($records as $record): ?>
        <url>
              <loc><?php echo CHtml::encode($model->createUrl($model->getRoute(), array('id' => $record[$idIndex], 'lang' => $model->language, 'title' => $record[$titleIndex]))); ?></loc> 
              <lastmod><?php echo Yii::app()->dateFormatter->format("yyyy-MM-dd:THH:mm:ss+02:00", $record['publish_date']); ?></lastmod> 
        </url>
    <?php endforeach; ?>
</urlset>