<?php
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
$widgetImage = Data::getInstance()->getPageImage('multimedia');
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/multimedia/default/index'));
if(!$breadcrumbs){
    $breadcrumbs[AmcWm::t("msgsbase.core", "Media Center")] = array('/multimedia/default/index');
}
$breadcrumbs[] = AmcWm::t("app", '_BLOCK_IMAGES_TITLE_');
if (AmcWm::app()->frontend['bootstrap']['use']) {
    $form = 'librariesBootstrapForm';
    $mediaList = "mediaBootstrapList";
} else {
    $mediaList = "mediaList";
}
$pageContent = $this->renderPartial(AmcWm::app()->appModule->getViewPathAlias() . ".default.librariesForm", array("galleries" => $galleries, 'galleryId' => $galleryId, 'labSelected' => "images"), true);
$pageContent .= $this->renderPartial(AmcWm::app()->appModule->getViewPathAlias() . ".default.{$mediaList}", array("activeGallery" => $activeGallery, 'route' => $route, 'labSelected' => "images"), true);
?>
<?php

$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'pageContentDesc' => AmcWm::t("app", '_MULTIMEDIA_BRIEF_'),
    'contentData' => $pageContent,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Media Center'),
    'pageContentPreTitle' => AmcWm::t("app", '_BLOCK_IMAGES_TITLE_'),
));
?>