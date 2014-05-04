<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Sections") => array('/backend/sections/default/index'),
    AmcWm::t("msgsbase.core", "Supervisor") => array('/backend/sections/default/supervisors'),
    AmcWm::t("msgsbase.core", "Edit"),
);
$this->sectionName = AmcWm::t("msgsbase.core", $contentModel->name);
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_supervisor', 'image_id' => 'save'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/sections/default/supervisors'), 'id' => 'supervisor_list', 'image_id' => 'back'),
    ),
));
?>
<div class="form">
    <?php
    $model = $contentModel->getParentContent();

    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Supervisor data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
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
            <?php echo $form->labelEx($translatedModel, 'name'); ?>
            <?php echo $form->textField($translatedModel, 'name', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($translatedModel, 'name'); ?>
        </div>
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Sex'); ?></span>:
            <span class="translated_org_item">
                <?php echo $model->getSexLabel() ?>
            </span>
        </div>        
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Email'); ?></span>:
            <span class="translated_org_item">
                <?php echo $model->email ?>
            </span>
        </div>       
        <div class="row">
                 <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Country'); ?></span>:
            <span class="translated_org_item">
                <?php echo $model->country->getCountryName() ?>
            </span>
        </div>        

    </fieldset>            

    <?php $this->endWidget(); ?>

</div><!-- form -->