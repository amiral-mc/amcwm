<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Parcels") => array('/backend/parcels/default/index'),
    AmcWm::t("msgsbase.carriers", "Shipping Companies") => array('/backend/parcels/carriers/index'),
    AmcWm::t("amcTools", "Translate"),
);
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_record', 'image_id' => 'save');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/parcels/carriers/index'), 'id' => 'records_list', 'image_id' => 'back');
$this->sectionName = $contentModel->carrier_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
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
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
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
            <?php echo $form->labelEx($translatedModel, 'carrier_name'); ?>
            <?php echo $form->textField($translatedModel, 'carrier_name', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($translatedModel, 'carrier_name'); ?>
        </div>           
    </fieldset>
    <?php $this->endWidget(); ?>
</div><!-- form -->