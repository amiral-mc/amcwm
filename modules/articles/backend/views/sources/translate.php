<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.news", "Articles")=>array('/backend/articles/default/index'),
    AmcWm::t("msgsbase.sources", "Sources") => array('/backend/articles/sources/index'),
    AmcWm::t("amcTools", "Translate"),
);
$this->sectionName = $contentModel->source;
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'translate_source', 'image_id' => 'save'),        
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/sources/index'), 'id' => 'sources_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
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
    ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
    <fieldset>                
        <legend></legend>
        <div class="row">
            <span class="translated_label">
                <?php echo AmcWm::t("amcTools", "Language"); ?>
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
            <?php echo $form->labelEx($translatedModel, 'source'); ?>
            <?php echo $form->textField($translatedModel, 'source', array('size' => 100, 'maxlength' => 100)); ?>
            <?php echo $form->error($translatedModel, 'source'); ?>
        </div>           
    </fieldset>
    <?php $this->endWidget(); ?>
</div><!-- form -->