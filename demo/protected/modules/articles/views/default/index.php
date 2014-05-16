<?php

$widgetImage = Data::getInstance()->getPageImage('articles', null, '', '');

$virtualModule = $this->module->appModule->currentVirtual;
$msgsBase = ($virtualModule == "articles") ? "msgsbase.core" : "msgsbase.{$virtualModule}";
$breadcrumbs[] = AmcWm::t($msgsBase, "Articles");
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
$this->widget('amcwm.widgets.SectionsList', array(
    'id' => 'sections_list',
    'items' => $sections,
    'wdgtSeparator' => array("data" => null, "after" => "0"),
    'title' => AmcWm::t($msgsBase, "Latest Articles"),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
));
?>
