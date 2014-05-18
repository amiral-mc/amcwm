<?php
$currentAppLang = Yii::app()->getLanguage();
$dir = Yii::app()->getLocale()->getOrientation();
if ($dir == "rtl") {
    $float = "right";    
} else {
    $align = "right";
}
?>
<?php $this->beginContent('layouts.main'); ?>
<div class="content_wrapper_bg">
    <div id="content_wrapper">
        <div>
            <div style="float:<?php echo $float; ?>;" id="pageContentImage">
                <?php
                $widgetImage = Yii::app()->request->baseUrl . '/images/front/' . Yii::app()->getLanguage() . '/KPI_default.jpg';
                echo CHtml::image($widgetImage, Yii::t('app', 'Reports'), array("class" => "top_photo"));
                ?>
            </div>
            <div id="internal_content_sec_left">
                <?php
                echo $this->getPositionData(2);
                ?>
            </div>
        </div>    
        <div style="clear: both;height: 1px;"></div>
        <div id="internal_content_wide">
            <div class="internal_content_wrapper">
                <?php echo $content; ?>          
            </div>		
        </div>
    </div>
</div>

<?php $this->endContent(); ?>