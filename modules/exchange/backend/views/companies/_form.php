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
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->error($model, 'published'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'exchange_id'); ?>
            <?php echo $form->dropDownList($model, 'exchange_id', CHtml::listData(Exchange::model()->findAll(array('order' => 'exchange_name DESC')), 'exchange_id', 'exchange_name')); ?>
            <?php echo $form->error($model, 'exchange_id'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'company_name'); ?>
            <?php echo $form->textField($model, 'company_name', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'company_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'code'); ?>
            <?php echo $form->textField($model, 'code', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'code'); ?>
        </div>
    </fieldset>        


    <?php $this->endWidget(); ?>

</div><!-- form -->