<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => $formId,
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => '<span class="required">*</span>')) ?>.</p>
    <?php echo $form->errorSummary($model); ?>
    <!--    <div class="row">
            <fieldset>
                <legend><?php echo AmcWm::t("msgsbase.core", "General Options"); ?>:</legend>
    <?php echo $form->checkBox($model, 'published'); ?>
    <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
    
            </fieldset>
        </div>-->
    <div class="row">

        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Video Options"); ?>:</legend>
            <?php //echo Yii::t("sms", "Add either external video or upload a new video file, with its thumb view"); ?>            
            <?php
            $videoMaxSize = Yii::app()->params["multimedia"]['smsVideos']['size'] / 1024 / 1024;
            $video = NULL;
            if (!$model->isNewRecord) {
                $videoFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->params["multimedia"]['smsVideos']['path']) . "/" . $model->getVideoName();
                if (is_file($videoFile)) {
                    $video = Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/" . Yii::app()->params["multimedia"]['smsVideos']['path']. "/" . $model->getVideoName();
                }
            }
            ?>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'videoFile'); ?>
        <?php echo $form->fileField($model, 'videoFile'); ?>
        <?php echo AmcWm::t("msgsbase.core", "Video File notes") . $videoMaxSize . "M" ?>
        <?php echo $form->error($model, 'videoFile'); ?>        
    </div>
    <div style="padding: 5px;">
<?php if ($video) {
            echo CHtml::link($video, $video, array('target' => '_bkank'));
        } ?>
    </div>
</fieldset>
</div>
<div class="row">
    <fieldset>
        <div class="row">
            <?php
            if ($model->isNewRecord) {
                $model->content_lang = 'ar';
                $model->video_header = AmcWm::t("msgsbase.core", "News videos for {day}", array('{day}'=>date("d/m/Y")));
            }
            ?>
            <?php echo $form->labelEx($model, 'content_lang'); ?>
<?php echo $form->dropDownList($model, 'content_lang', $this->getLanguages()); ?>
            <?php echo $form->error($model, 'content_lang'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'video_header'); ?>
<?php echo $form->textField($model, 'video_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($model, 'video_header'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php
            $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                'model' => $model,
                'attribute' => 'description',
                'editorTemplate' => 'full',
                'htmlOptions' => array(
                    'style' => 'height:300px; width:600px;'
                ),
                    )
            );
            ?>
<?php echo $form->error($model, 'description'); ?>
        </div>
    </fieldset>        
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->