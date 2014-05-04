<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('Form', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
//        'onCallMethod' => array($this, "formHandler"),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>

    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam()); ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php
    $models[] = $model;
    $models[] = $contentModel;
    foreach ($contentModel->attachment as $attachmentModel){
        $models[] = $attachmentModel;
    }
    echo $form->errorSummary($models);
    ?>
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

                <?php echo $form->checkBox($model, 'published'); ?>
                <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
            </div>
        </fieldset>
    </div>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Event Data"); ?>:</legend>      
        <div class="row">                       
            <?php echo $form->labelEx($model, 'section_id'); ?>
            <?php echo $form->dropDownList($model, 'section_id', Sections::getSectionsList(), array('empty' => Yii::t('zii', 'Not set'))); ?>
            <?php echo $form->error($model, 'section_id'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'event_date'); ?>           
            <?php echo $form->calendarField($model, 'event_date', array('class' => 'datebox', 'dateOptions' => array("dateOnly" => 0))); ?>           
            <?php echo $form->error($model, 'event_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'location'); ?>
            <?php echo $form->textField($contentModel, 'location', array('size' => 150, 'maxlength' => 150)); ?>
            <?php echo $form->error($contentModel, 'location'); ?>
        </div>

        <div class="row">
            <?php
            if ($model->isNewRecord) {
                $model->country_code = 'EG';
            }
            ?>
            <?php echo $form->labelEx($model, 'country_code'); ?>
            <?php echo $form->dropDownList($model, 'country_code', $this->getCountries(true)); ?>
            <?php echo $form->error($model, 'country_code'); ?>
        </div>


        <div class="row">
            <?php echo $form->labelEx($contentModel, 'event_header'); ?>
            <?php echo $form->textField($contentModel, 'event_header', array('size' => 500, 'maxlength' => 500)); ?>
            <?php echo $form->error($contentModel, 'event_header'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'event_detail'); ?>
            <?php echo $form->error($contentModel, 'event_detail'); ?>
            <?php echo $form->richTextField($contentModel, 'event_detail', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>           
        </div>        
        <div class="row">
            <?php 
                echo $form->attachmentField($contentModel, 'attachment', array("id" => "attachment_area")); 
            ?>
        </div>     
    </fieldset>

    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("amcBack", "Publish to the social media sites"); ?>:</legend>
            <?php //echo $form->labelEx($model->article, 'socialIds');     ?>
            <span>
                <?php echo $form->checkBoxList($model, 'socialIds', $this->getSocials(), array("separator" => "<br />", 'labelOptions' => array('class' => 'checkbox_label'))); ?>
            </span>
            <?php echo $form->error($model, 'socialIds'); ?>
        </fieldset>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->