<?php
$model = $contentModel->getParentContent();
$sectionTree = Sections::getSectionTree($model->section_id);
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Events") => array('/backend/events/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->event_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_event', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/events/default/index'), 'id' => 'events_list', 'image_id' => 'back'),
    ),
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
    <div class="row">
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
    </div>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Event Data"); ?>:</legend>      
        <!--  Add Parent sections here       -->
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
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Event Date'); ?></span>:
                <span class="translated_org_item">
                    <?php echo Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->event_date); ?>
                </span>
            </div>
            <div class="row">
                <?php echo $form->labelEx($translatedModel, 'location'); ?>
                <?php echo $form->textField($translatedModel, 'location', array('size' => 150, 'maxlength' => 150)); ?>
                <?php echo $form->error($translatedModel, 'location'); ?>
            </div>
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Country'); ?></span>:
                <span class="translated_org_item">
                    <?php echo $model->country->getCountryName() ?>
                </span>
            </div>                   
            <div class="row">
                <?php echo $form->labelEx($translatedModel, 'event_header'); ?>
                <?php echo $form->textField($translatedModel, 'event_header', array('size' => 500, 'maxlength' => 500)); ?>
                <?php echo $form->error($translatedModel, 'event_header'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($translatedModel, 'event_detail'); ?>
                <?php echo $form->error($translatedModel, 'event_detail'); ?>
                <?php
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                    'model' => $translatedModel,
                    'attribute' => 'event_detail',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:630px;'
                    ),
                        )
                );
                ?>
            </div>        
            <div class="row">
                <?php echo $form->attachmentField($translatedModel, 'attachment', array("id" => "attachment_area", 'attachOptions'=> array('translateOnly'=>true))); ?>
            </div>     
    </fieldset>
    <?php $this->endWidget(); ?>
</div><!-- form -->