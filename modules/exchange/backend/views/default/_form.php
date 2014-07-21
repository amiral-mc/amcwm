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
            <?php echo $form->labelEx($model, 'exchange_name'); ?>
            <?php echo $form->textField($model, 'exchange_name', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'exchange_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'currency'); ?>
            <?php echo $form->dropDownList($model, 'currency', $this->getCurrencies()); ?>
            <?php echo $form->error($model, 'currency'); ?>
        </div>
    </fieldset>


    <?php $this->endWidget(); ?>

</div><!-- form -->