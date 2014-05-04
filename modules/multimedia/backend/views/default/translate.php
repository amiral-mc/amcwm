<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
   AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->gallery_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_gallery', 'image_id' => 'save'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/default/index'), 'id' => 'galleries_list', 'image_id' => 'back'),
    ),
));
?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => '<span class="required">*</span>')) ?>.</p>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
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
        </div>
    </fieldset> 
    <br />
    <fieldset>              
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'gallery_header'); ?>
            <?php echo $form->textField($translatedModel, 'gallery_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($translatedModel, 'gallery_header'); ?>
        </div>
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Section'); ?></span>:
            <span class="translated_org_item">
                <?php
                $sectionName = Sections::drawSectionPath($model->section_id);
                if ($sectionName) {
                    echo $sectionName;
                } else {
                    echo Yii::t('zii', 'Not set');
                }
                ?>
            </span>
        </div>            
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Country'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($model->countryCode) {
                    echo $model->countryCode->getCountryName();
                } else {
                    echo Yii::t('zii', 'Not set');
                }
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

    <?php $this->endWidget(); ?>

</div><!-- form -->