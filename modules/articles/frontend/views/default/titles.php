<?php

$this->pageTitle = $data['pageSiteTitle'] . ' - ' . $this->pageTitle;
$virtualModule = $data['task']->getSettings()->currentVirtual;
$msgsBase = ($virtualModule == "articles") ? "msgsbase.core" : "msgsbase.{$virtualModule}";
$extension = "ContentTitlesList";
$removeLastItem = (bool) AmcWm::app()->request->getParam('menu');
if ($virtualModule == "news" || $virtualModule == "essays") {
    $extension = "ArticlesListing";
    $params = $data['task']->getActionParams();
    $removeLastItem = !isset($params['id']);
    //$removeLastItem = !$data['sectionId'];    
}

if (!$data['pageContentTitle']) {
    $data['pageContentTitle'] = AmcWm::t($msgsBase, "Articles");
}
if (!$data['widgetTitle']) {
    $data['widgetTitle'] = AmcWm::t($msgsBase, "Articles");
}
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/articles/default/sections', 'id' => $data['sectionId']), $removeLastItem);
if (isset($data['pageSiteTitle'])) {
    Yii::app()->clientScript->registerMetaTag($data['pageSiteTitle'], "description");
    Yii::app()->clientScript->registerMetaTag($data['pageSiteTitle'], "og:title");
}
if (isset($data['keywords'])) {
    Yii::app()->clientScript->registerMetaTag($data["keywords"]);
}
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");

$widgetImage = Data::getInstance()->getPageImage('articles', $data['widgetImage'], null, '');

$this->widget("widgets.{$extension}", array(
    'id' => 'sections_list',
    'contentData' => $data['pageContent'],
    'items' => $data['itemsList'],
    'viewOptions' => $data['viewOptions'],
    'title' => $data['widgetTitle'],
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'descriptionKey' => $data['descriptionKey'],
    'pageContentTitle' => $data['pageContentTitle'],
));
?>