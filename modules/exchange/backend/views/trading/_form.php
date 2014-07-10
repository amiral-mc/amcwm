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
            <?php echo $form->labelEx($model, 'company_id'); ?>
            <?php echo $form->dropDownList($model, 'company_id', CHtml::listData(ExchangeCompanies::model()->findAll(array('order' => 'company_name ASC')), 'company_id', 'company_name')); ?>
            <?php echo $form->error($model, 'company_id'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'index'); ?>
            <?php echo $form->textField($model, 'index'); ?>
            <?php echo $form->error($model, 'index'); ?>
        </div>
        <div class="row">                       
            <?php echo $form->labelEx($model, 'percentage'); ?>
            <?php echo $form->textField($model, 'percentage'); ?>
            <?php echo $form->error($model, 'percentage'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'net'); ?>
            <?php echo $form->textField($model, 'net'); ?>
            <?php echo $form->error($model, 'net'); ?>
        </div>
    </fieldset>


    <?php     
    $this->endWidget(); ?>

</div><!-- form -->