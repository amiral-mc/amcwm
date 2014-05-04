<?php
$this->pageTitle = $data['pageSiteTitle'] . ' - ' . $this->pageTitle;
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/articles/default/sections', 'id'=>$data['sectionId']),true);
//$msgsBase = ($virtualModule == "articles") ? "msgsbase.core" : "msgsbase.{$virtualModule}";
if (isset($data['pageSiteTitle'])) {
    Yii::app()->clientScript->registerMetaTag($data['pageSiteTitle'], "description");
    Yii::app()->clientScript->registerMetaTag($data['pageSiteTitle'], "og:title");
}
if (isset($data['keywords'])) {
    Yii::app()->clientScript->registerMetaTag($data["keywords"]);
}
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");

$widgetImage = Data::getInstance()->getPageImage('articles', $data['widgetImage'], null, '');

$this->widget('amcwm.widgets.SectionsLinks', array(
    'id' => 'sections_list',
    'contentData' => $data['pageContent'],
    'items' => $data['itemsList'],
//    'viewOptions' => $data['viewOptions'],
    'title' => $data['widgetTitle'],
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $data['pageContentTitle'],
));
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
?>