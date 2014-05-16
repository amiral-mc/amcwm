<?php
$this->pageTitle = $data['pageSiteTitle'] . ' - ' . $this->pageTitle;
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/articles/default/sections', 'id'=>$data['sectionId']),false);
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
$pageContentLabel = null;
$pageContentSloganLabel = $data['widgetTitle'];
if (count($breadcrumbs)) {
    $pageContentLabel = Data::getInstance()->getBreadcrumbsContentParentLabel($breadcrumbs);
}

if (!$pageContentLabel) {
    $pageContentLabel = $pageContentSloganLabel;
    $pageContentSloganLabel = null;
}
$this->widget('amcwm.widgets.ContentColsList', array(
    'id' => 'sections_list',
    'pageContentDesc' => $data['pageContent'],
    'items' => $data['itemsList'],
    'viewOptions' => $data['viewOptions'],
//    'title' => $data['widgetTitle'],
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $pageContentLabel,
    'pageContentPreTitle' => $pageContentSloganLabel,
));
?>