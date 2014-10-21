<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/branches/index'),
);
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_branch', 'image_id' => 'save');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/directory/branches/index', 'cid' => $this->getCompanyId()), 'id' => 'modules_list', 'image_id' => 'back');

$this->breadcrumbs[] = AmcWm::t("amcTools", "Translate");
$this->sectionName = $contentModel->branch_name;
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
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
    <fieldset>                
        <legend><?php echo AmcWm::t("msgsbase.core", "Branch data"); ?>:</legend>
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
            <?php echo $form->labelEx($translatedModel, 'branch_name'); ?>
            <?php echo $contentModel->branch_name;?><br />
            <?php echo $form->textField($translatedModel, 'branch_name', array('size' => 65, 'maxlength' => 100)); ?>
            <?php echo $form->error($translatedModel, 'branch_name'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'branch_address'); ?>
            <?php echo $contentModel->branch_address;?><br />
            <?php echo $form->extendableField($translatedModel, 'branch_address' , 'textField', array('translateOnly'=>true, 'htmlOptions'=>array('size' => 65, 'maxlength' => 150))); ?>
            <?php echo $form->error($translatedModel, 'branch_address'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'city'); ?>
            <?php echo $contentModel->city;?><br />
            <?php echo $form->textField($translatedModel, 'city', array('size' => 65, 'maxlength' => 100)); ?>
            <?php echo $form->error($translatedModel, 'city'); ?>
        </div>

    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->