<?php $this->beginClip('mediaContent'); ?>
<p class="section_brief">
    <?php echo AmcWm::t("app", '_MULTIMEDIA_BRIEF_'); ?>    
</p>
<?php $this->endClip('mediaContent'); ?>
<?php
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
$widgetImage = Data::getInstance()->getPageImage('multimedia');
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/multimedia/default/index'));
if(!$breadcrumbs){
    $breadcrumbs[AmcWm::t("msgsbase.core", "Media Center")] = array('/multimedia/default/index');
}
$breadcrumbs[] = AmcWm::t("app", '_BLOCK_VIDEOS_TITLE_');
$pageContent = $this->clips['mediaContent'];
if (AmcWm::app()->frontend['bootstrap']['use']) {
    $form = 'librariesBootstrapForm';
    $mediaList = "mediaBootstrapList";
} else {
    $form = 'librariesForm';
    $mediaList = "mediaList";
}
$pageContent .= $this->renderPartial(AmcWm::app()->appModule->getViewPathAlias() . ".default.{$form}", array("galleries" => $galleries, 'galleryId' => $galleryId, 'labSelected' => "videos"), true);
$pageContent .= $this->renderPartial(AmcWm::app()->appModule->getViewPathAlias() . ".default.{$mediaList}", array("activeGallery" => $activeGallery, 'route' => $route, 'labSelected' => "videos"), true);
?>
<?php

$this->widget('PageContentWidget', array(
    'id' => 'videos-list',
    'contentData' => $pageContent,
    'title' => AmcWm::t("app", '_BLOCK_VIDEOS_TITLE_'),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Media Center'),
));
?>