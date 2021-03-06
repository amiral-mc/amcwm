<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Job requests") => array('/backend/jobs/default/index'),
);
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_section', 'image_id' => 'save');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/jobs/jobs/index'), 'id' => 'modules_list', 'image_id' => 'back');

$this->breadcrumbs[] = AmcWm::t("amcTools", "Translate");
$this->sectionName = $contentModel->job;
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
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
    <fieldset>                
        <legend><?php echo AmcWm::t("msgsbase.core", "Item data"); ?>:</legend>
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
            </span>
            <?php echo $form->labelEx($translatedModel, 'job'); ?>
            <?php echo $contentModel->job ?><br />
            <?php echo $form->textField($translatedModel, 'job', array('size' => 150, 'maxlength' => 150)); ?>
            <?php echo $form->error($translatedModel, 'job'); ?>
        </div>
        
         <div class="row">
                <?php echo $form->labelEx($translatedModel, 'job_description'); ?>
                <?php echo $form->error($translatedModel, 'job_description'); ?>
                <?php echo $contentModel->job_description ?><br />
                <?php
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                    'model' => $translatedModel,
                    'attribute' => 'job_description',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:630px;'
                    ),
                        )
                );
                ?>            
            </div>
    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->