<?php
$model = $contentModel->getParentContent();
$sectionTree = Sections::getSectionTree($model->section_id);
$formId = Yii::app()->params["adminForm"];
$options = $this->module->appModule->options;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Products") => array('/backend/products/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->product_name;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_product', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/products/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<div class="form">
    <?php
    $form = $this->beginWidget('Form', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
    ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "General Option"); ?>:</legend>
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

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Details"); ?>:</legend>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'product_name'); ?>
            <?php echo $form->textField($translatedModel, 'product_name', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($translatedModel, 'product_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'product_brief'); ?>
            <?php echo $form->error($translatedModel, 'product_brief'); ?>
            <?php echo $form->richTextField($translatedModel, 'product_brief', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'product_description'); ?>
            <?php echo $form->error($translatedModel, 'product_description'); ?>
            <?php echo $form->richTextField($translatedModel, 'product_description', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'product_specifications'); ?>
            <?php echo $form->error($translatedModel, 'product_specifications'); ?>
            <?php echo $form->richTextField($translatedModel, 'product_specifications', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>
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
                'wordsCount' => Yii::app()->params["limits"]["wordsCount"], // words in each box count
                'htmlOptions' => array(),
                    )
            );
            ?>            
        </div>     
    </fieldset>
    <?php $this->endWidget(); ?>
</div><!-- form -->    