<?php
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
$widgetImage = Data::getInstance()->getPageImage('multimedia');
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/multimedia/default/index'));
if(!$breadcrumbs){
    $breadcrumbs[AmcWm::t("msgsbase.core", "Media Center")] = array('/multimedia/default/index');
}
$breadcrumbs[] = AmcWm::t("app", '_BLOCK_PRESENTATIONS_TITLE_');
$pageContent = $this->renderPartial("amcwm.modules.multimedia.frontend.views.default.librariesForm", array("galleries" => array(), 'galleryId' => null, 'labSelected' => "presentations", 'msgAlias'=>$presentationMsgAlias), true);
$pageContent .= $this->renderPartial( "{$presentationViewPathAlias}.default.presentationList", array('directoryData' => $directoryData, 'mediaSettings'=>$docsMediaSettings), true);
?>
<?php

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'pageContentDesc' => AmcWm::t("app", '_MULTIMEDIA_BRIEF_'),
    'contentData' => $pageContent,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Media Center'),
    'pageContentPreTitle' => AmcWm::t("app", '_BLOCK_PRESENTATIONS_TITLE_'),
));
?>