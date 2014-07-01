<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $model)); ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <fieldset>                
        <div class="row">
            <?php echo $form->labelEx($model, 'server_name'); ?>
            <?php echo $form->textField($model, 'server_name', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'server_name'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'header_code'); ?>
            <?php echo $form->textArea($model, 'header_code', array('dir' => 'ltr')); ?>
            <?php echo $form->error($model, 'header_code'); ?>
        </div>
    </fieldset>        


    <?php $this->endWidget(); ?>

</div><!-- form -->