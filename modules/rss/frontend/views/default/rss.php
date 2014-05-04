<?php

Yii::import('application.modules.rss.components.feed.*');
$feed = new EFeed();

$feed->title = AmcWm::t("amcFront", "Arab News Agency");
$feed->description = AmcWm::t("app", "_website_name_");

//$feed->setImage('Testing RSS 2.0 EFeed class', 'http://www.ramirezcobos.com/rss', 'http://www.yiiframework.com/forum/uploads/profile/photo-7106.jpg');

$feed->addChannelTag('language',  Yii::app()->user->getCurrentLanguage());
$feed->addChannelTag('pubDate', date(DATE_RSS, time()));
$feed->addChannelTag('link', Yii::app()->request->getHostInfo(). Yii::app()->request->baseUrl);

// * self reference

$feed->addChannelTag('atom:link', Yii::app()->request->getHostInfo() . Html::createUrl($this->getRoute(), $this->getActionParams()));


foreach ($rssItems As $rssItem) {
    //$t
    $item = $feed->createNewItem();
    $item->title = $rssItem['title'];
    $item->link =  Yii::app()->request->getHostInfo() . $rssItem['link'];
    
    $item->date = $rssItem['publish_date'];
    $item->description = $rssItem['article_detail'];
    
    //$item->setEncloser('http://example.com', '1283629', 'audio/mpeg');

    //$item->addTag('author', '');
    $item->addTag('guid', Yii::app()->request->getHostInfo() .$rssItem['link']);

    $feed->addItem($item);
}

$feed->generateFeed();
//print_r($rssItems);
?>