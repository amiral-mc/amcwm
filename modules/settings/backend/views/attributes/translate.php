<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Configuration") => array('/backend/settings/default/index'),
    AmcWm::t("msgsbase.core", "Attributes") => array('/backend/settings/attributes/index'),
    AmcWm::t("amcTools", "Edit"),
);
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_section', 'image_id' => 'save');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/settings/attributes/index'), 'id' => 'attributes_list', 'image_id' => 'back');

$this->breadcrumbs[] = AmcWm::t("amcTools", "Translate");
$this->sectionName = $contentModel->label;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('Form', array(
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
        <legend><?php echo AmcWm::t("msgsbase.core", "Attribute Options"); ?>:</legend>
        <div class="row">
            <span class="translated_label">
                <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
            </span>
            :
            <span class="translated_org_item">
                <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
            </span>
             <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'New Type'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($model->is_new_type) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
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
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Module'); ?></span>:
            <span class="translated_org_item">
                <?php
                    echo  $model->getModuleName();
                ?>
            </span>
        </div>
        <div class="row">
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Attribute Type'); ?></span>:
            <span class="translated_org_item">
                <?php
                    echo  $model->getAttributeType();
                ?>
            </span>
        </div>
        <div class="row">
            </span>
            <?php echo $form->labelEx($translatedModel, 'label'); ?>
            <?php echo $form->textField($translatedModel, 'label', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($translatedModel, 'label'); ?>
        </div>        
    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->