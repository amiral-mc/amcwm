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
$breadcrumbs[] = AmcWm::t("app", '_BLOCK_PRESENTATIONS_TITLE_');
$pageContent = $this->clips['mediaContent'];
if(!isset($presentationViewPathAlias)){
    $presentationViewPathAlias = AmcWm::app()->appModule->getViewPathAlias();
}

$pageContent .= $this->renderPartial("{$presentationViewPathAlias}.default.librariesForm", array("galleries" => array(), 'galleryId' => null, 'labSelected' => "presentations", 'msgAlias'=>$presentationMsgAlias), true);
$pageContent .= $this->renderPartial( "{$presentationViewPathAlias}.default.presentationList", array('directoryData' => $directoryData, 'mediaSettings'=>$docsMediaSettings), true);
?>
<?php

$this->widget('PageContentWidget', array(
    'id' => 'videos-list',
    'contentData' => $pageContent,
    'title' => AmcWm::t("app", '_BLOCK_PRESENTATIONS_TITLE_'),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("{$presentationMsgAlias}.core", 'Media Center'),
));