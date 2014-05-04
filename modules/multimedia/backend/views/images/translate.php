<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $this->gallery->gallery_id),
    AmcWm::t("msgsbase.core",  "_{$this->getId()}_title_") => array('/backend/multimedia/'.$this->getId().'/index', 'gid' => $this->gallery->gallery_id),
   AmcWm::t("amcTools", "Edit"),
);

$this->sectionName = $contentModel->image_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_image', 'image_id' => 'save'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/'.$this->getId().'/index', 'gid' => $this->gallery->gallery_id), 'id' => 'images_list', 'image_id' => 'back'),
    ),
));
?>
<div class="form">

    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
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
    <fieldset>   
        <legend><?php echo AmcWm::t("msgsbase.core", "Image Details"); ?>:</legend>       
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'image_header'); ?>
            <?php echo $form->textField($translatedModel, 'image_header', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($translatedModel, 'image_header'); ?>
        </div>

        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Image File'); ?></span>:<br />
            <?php
            $drawImage = null;
            if (!$model->isNewRecord) {
                $drawImage = Yii::app()->baseUrl . "/" . Yii::app()->getController()->imageInfo['path'] . "/" . $model->image_id . "." . $model->ext;
                $drawImage = str_replace("{gallery_id}", $model->gallery_id, $drawImage);
            }
            ?>
            <span class="translated_org_item">
                <?php if ($drawImage): ?>
                    <?php echo Chtml::image($drawImage, "", array("width" => 100)); ?>
                <?php endif; ?>
            </span>
        </div>      
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
            <?php echo $form->labelEx($translatedModel, 'description'); ?>            
            <?php echo $form->textArea($translatedModel, 'description'); ?>
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
    <?php if ($this->getModule()->appModule->useKeywords): ?>
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
                    'wordsCount' => Yii::app()->params["limits"]["wordsCount"], // words in each box count
                    'htmlOptions' => array(),
                        )
                );
                ?>            
            </div>     
        </fieldset>
    <?php endif;  ?>
    <?php $this->endWidget(); ?>

</div><!-- form -->