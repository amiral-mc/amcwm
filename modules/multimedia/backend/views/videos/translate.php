<?php
$mediaPaths = $this->getModule()->appModule->mediaPaths;
$model = $contentModel->getParentContent();
$galleryId = $this->gallery->getParentContent()->gallery_id;
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $galleryId),
    AmcWm::t("msgsbase.core", "Videos") => array('/backend/multimedia/videos/index', 'gid' => $galleryId),
   AmcWm::t("amcTools", "Edit"),
);
$tools = array();
$tools[] = array('label' =>AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_video', 'image_id' => 'save');
$tools[] = array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'url' => array('/backend/multimedia/videos/comments', 'gid' => $galleryId, 'item' => $model->video_id), 'id' => 'manage_videos_comments', 'image_id' => 'comments');
if ($this->getModule()->appModule->useDopeSheet) {
    $tools[] = array('label' => AmcWm::t("msgsbase.core", 'Dope Sheet'), 'url' => array('/backend/multimedia/videos/dopeSheet', 'gid' => $galleryId, 'mmId' => $model->video_id), 'id' => 'manage_videos_comments', 'image_id' => 'dope_sheet');
}
$tools[] = array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/videos/index', 'gid' => $galleryId), 'id' => 'videos_list', 'image_id' => 'back');
$this->sectionName = $contentModel->video_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $tools));
?>
<div class="form">
    <?php
    $model = $contentModel->getParentContent();
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

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => '<span class="required">*</span>')) ?>.</p>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
    <?php echo CHtml::hiddenField("gid", $galleryId); ?>
    <div>
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "General Options"); ?>:</legend>
            <div class="row">                       
                <span class="translated_label">
                    <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
                </span>
                :
                <span class="translated_org_item">
                    <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
                </span>
            </div>              
            <div class="row">
                <?php
                $actionParams = $this->getActionParams();
                if (array_key_exists('tlang', $actionParams)) {
                    unset($actionParams['tlang']);
                }
                $translateRoute = Html::createUrl($this->getRoute());
                ?> 
                <?php echo CHtml::label(AmcWm::t("amcTools", "Translate To"), "tlang") ?>
                <?php echo CHtml::dropDownList("tlang", $translatedModel->content_lang, $this->getTranslationLanguages(), array("onchange" => "FormActions.translationChange('$translateRoute', " . CJSON::encode($actionParams) . ");")); ?>
            </div>
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Published'); ?></span>:
                <span class="translated_org_item">
                    <?php
                    if ($model->published) {
                        echo AmcWm::t("amcFront", "Yes");
                    } else {
                        echo AmcWm::t("amcFront", "No");
                    }
                    ?>
                </span>
                &nbsp;
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'In Slider'); ?></span>:
                <span class="translated_org_item">
                    <?php
                    if ($model->in_slider) {
                        echo AmcWm::t("amcFront", "Yes");
                    } else {
                        echo AmcWm::t("amcFront", "No");
                    }
                    ?>
                </span>
            </div>
        </fieldset>
    </div>
    <div>

        <?php
        $drawImage = $thumbImage = null;
        if (!$model->isNewRecord && isset($model->internalVideos->img_ext)) {
            $drawImage = Yii::app()->baseUrl . "/" . $mediaPaths['videos']['thumb']['path'] . "/" . $model->video_id . "." . $model->internalVideos->img_ext . "?t=" . time();
            $drawImage = str_replace("{gallery_id}", $model->gallery_id, $drawImage);                                    
            $thumbImage = Chtml::image($drawImage, "", array("width" => 100));
        }
        ?>

        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "Video Options"); ?>:</legend>
            <?php //echo AmcWm::t("msgsbase.core", "Add either external video or upload a new video file, with its thumb view");  ?>
            <div style="padding: 5px;">
                <?php
                $video = NULL;
                if ($model->videoType == "externalVideos") {
                    $video = $model->videoURL;
                } else if ($model->videoType == "internalVideos" && $model->internalVideos !== NULL) {
                    $videoFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['videos']['path']) . "/" . $model->video_id . "." . $model->internalVideos->video_ext;
                    $videoFile = str_replace("{gallery_id}", $model->gallery_id, $videoFile);                                    
                    if (is_file($videoFile)) {                        
                        $video = Yii::app()->request->baseUrl . "/" . $mediaPaths['videos']['path'] . "/{$model->internalVideos->video_id}.{$model->internalVideos->video_ext}";
                        $video = str_replace("{gallery_id}", $model->gallery_id, $video);                                    
                    }
                }
                //die($video);
                if ($video) {
                    $this->widget('amcwm.widgets.videoplayer.VideoPlayer', array(
                        'id' => 'videoPlayer1',
                        'className' => 'videoPlayerClass',
                        'width' => 300,
                        'height' => 200,
                        'title' => 'Your video header text',
                        'video' => $video,
                            )
                    );
                }
                ?>
            </div>
            <div><?php echo $drawImage?></div>
        </fieldset>
    </div>
    <div class="row">
        <fieldset>
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Gallery'); ?></span>:
                <span class="translated_org_item">
                    <?php echo $this->gallery->gallery_header; ?>
                </span>
            </div>            
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'In Focus File'); ?></span>:
                <span class="translated_org_item">
                    <?php
                    $infocusName = $this->getInfocucName($model->infocusId);
                    if ($infocusName) {
                        echo $infocusName;
                    } else {
                        echo Yii::t('zii', 'Not set');
                    }
                    ?>
                </span>
            </div>   
            <div class="row">
                <?php echo $form->labelEx($translatedModel, 'video_header'); ?>
                <?php echo $form->textField($translatedModel, 'video_header', array('size' => 60, 'maxlength' => 500)); ?>
                <?php echo $form->error($translatedModel, 'video_header'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($translatedModel, 'description'); ?>
                <?php
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                    'model' => $translatedModel,
                    'attribute' => 'description',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:600px;'
                    ),
                        )
                );
                ?>
                <?php echo $form->error($translatedModel, 'description'); ?>
            </div>
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Publish Date'); ?></span>:
                <span class="translated_org_item"><?php echo Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->publish_date); ?>
                </span>
            </div>
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Expire Date'); ?></span>:
                <span class="translated_org_item">
                    <?php
                    $expireDate = ($model->expire_date) ? Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->expire_date) : AmcWm::t("msgsbase.core", "No expiry date");
                    echo $expireDate;
                    ?>
                </span>
            </div>

        </fieldset>

        <fieldset>
            <legend><?php echo AmcWm::t("amcBack", "Tags"); ?>:</legend>
            <div class="row">
                <?php
                $this->widget('Keywards', array(
                    'model' => $translatedModel,
                    'attribute' => "tags[]",
                    //                    'name' => "tags",
                    'values' => $translatedModel->tags,
                    'formId' => $formId,
                    'container' => "keywordItems",
                    'delimiter' => Yii::app()->params["limits"]["delimiter"],
                    'elements' => Yii::app()->params["limits"]["elements"], // keyword boxs count
                    'wordsCount' => Yii::app()->params["limits"]["wordsCount"], //  words in each box count
                    'htmlOptions' => array(),
                        )
                );
                ?>            
            </div>     
        </fieldset>
    </div>   

    <?php $this->endWidget(); ?>

</div><!-- form -->
