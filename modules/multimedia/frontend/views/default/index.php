<?php
$allOptions = $this->module->appModule->options;
$allOptions['default']['blockImages']['videos'];
$havePresentations = isset($allOptions['default']['integer']['presentationId']) && $allOptions['default']['integer']['presentationId'];
?>
<?php $this->beginClip('mediaOptions'); ?>
<p class="section_brief">
    <?php echo AmcWm::t("app", '_MULTIMEDIA_BRIEF_'); ?>    
</p>
<div class="wrapper">
    <div class="data_block first" >
        <?php if ($allOptions['default']['blockImages']['videos']): ?>                    
            <img src="<?php echo AmcWm::app()->baseUrl ?>/<?php echo trim($allOptions['default']['blockImages']['images'], "/") ?>" alt="<?php echo AmcWm::t("app", '_BLOCK_IMAGES_TITLE_'); ?>" />
        <?php endif; ?>
        <h3><?php echo Html::link(AmcWm::t("app", '_BLOCK_IMAGES_TITLE_'), array('/multimedia/default/images')); ?></h3>
        <p><?php echo AmcWm::t("app", '_BLOCK_IMAGES_INFO_'); ?></p>
    </div>	

    <div class="data_block" >        
        <?php if ($allOptions['default']['blockImages']['videos']): ?>                    
            <img src="<?php echo AmcWm::app()->baseUrl ?>/<?php echo trim($allOptions['default']['blockImages']['videos'], "/") ?>" alt="<?php echo AmcWm::t("app", '_BLOCK_VIDEOS_TITLE_'); ?>" />
        <?php endif; ?>
        <h3><?php echo Html::link(AmcWm::t("app", '_BLOCK_VIDEOS_TITLE_'), array('/multimedia/default/videos')); ?></h3>
        <p><?php echo AmcWm::t("app", '_BLOCK_VIDEOS_INFO_'); ?></p>
    </div>	
    <?php if ($havePresentations): ?>
        <div class="data_block" >
            <?php if ($allOptions['default']['blockImages']['presentations']): ?>                    
                <img src="<?php echo AmcWm::app()->baseUrl ?>/<?php echo trim($allOptions['default']['blockImages']['presentations'], "/") ?>" alt="<?php echo AmcWm::t("app", '_BLOCK_PRESENTATIONS_TITLE_'); ?>" />
            <?php endif; ?>
            <h3><?php echo Html::link(AmcWm::t("app", '_BLOCK_PRESENTATIONS_TITLE_'), array('/multimedia/default/presentations')); ?></h3>
            <p><?php echo AmcWm::t("app", '_BLOCK_PRESENTATIONS_INFO_'); ?></p>

        </div>		
    <?php endif; ?>

</div>
<?php $this->endClip('mediaOptions'); ?>
<?php
Yii::app()->clientScript->registerMetaTag(Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/images/fb_img.jpg", "og:image");
$widgetImage = Data::getInstance()->getPageImage('multimedia');
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array('/multimedia/default/index'));
if(!$breadcrumbs){
    $breadcrumbs[AmcWm::t("msgsbase.core", "Media Center")] = array('/multimedia/default/index');
}
$this->widget('PageContentWidget', array(
    'id' => 'media_center_options',
    'contentData' => $this->clips['mediaOptions'],
    'title' => AmcWm::t("msgsbase.core", 'Media Center'),
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => AmcWm::t("msgsbase.core", 'Media Center'),
));
?>