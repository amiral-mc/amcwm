<?php 
$smallPath = Yii::app()->request->getHostInfo() . Yii::app()->baseUrl . '/' . str_replace('/', DIRECTORY_SEPARATOR, Yii::app()->params["multimedia"]['articles']['list']['path']) . DIRECTORY_SEPARATOR;
$bigPath = Yii::app()->request->getHostInfo() . Yii::app()->baseUrl . '/' . str_replace('/', DIRECTORY_SEPARATOR, Yii::app()->params["multimedia"]['articles']['images']['path']) . DIRECTORY_SEPARATOR;
echo '<?xml version="1.0" encoding="utf-8" ?>' 
?> 
<provider>
      <name><?php echo Yii::app()->request->getHostInfo();?></name> 
      <newsType>text</newsType> 
      <logo><?php echo Yii::app()->request->getHostInfo() . Yii::app()->baseUrl . "/images/front/{$lang}/ana_email_logo.png"?></logo> 
      <language><?php echo $lang; ?></language> 
    <?php foreach($newsItems as $item):?>
    <item>
          <id><?php echo $item['article_id']?></id> 
          <time><?php echo $item['publish_date']?></time> 
        <title>
            <![CDATA[
            <?php echo $item['article_header']?>
            ]]> 
              </title>
        <metaData>
        <![CDATA[
        <?php echo str_replace("\n", ',', $item['tags']); ?>
        ]]> 
          </metaData>
        <smallImage>
            <![CDATA[
            <?php echo "$smallPath{$item['article_id']}.{$item['thumb']}";?>
            ]]> 
        </smallImage>
        <bigImage>
            <![CDATA[
                <?php echo "$bigPath{$item['article_id']}.{$item['thumb']}";?>
            ]]> 
        </bigImage>
        <shortDescription>
            <![CDATA[
                <?php echo Html::utfSubstring($item['article_detail'], 0, 200); ?>
            ]]> 
              </shortDescription>
        <fullDescription>
            <![CDATA[
            <?php echo strip_tags($item['article_detail'], '<br>, <p>') ?>
            ]]> 
        </fullDescription>
    </item>
    <?php endforeach;?>
</provider>