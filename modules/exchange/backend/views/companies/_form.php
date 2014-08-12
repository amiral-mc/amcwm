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
    if($contentModel->parentContent()->currency)
        $currency = $contentModel->parentContent()->currency;
    else
        $currency = Yii::app()->db->createCommand('SELECT currency FROM exchange WHERE exchange_id = ' . $eid)->queryScalar();
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($contentModel)); ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <fieldset>
        <div class="row">
            <?php echo $form->labelEx($contentModel->parentContent(), 'published', array("style" => 'display:inline;')); ?>
            <?php echo $form->checkBox($contentModel->parentContent(), 'published', array('style' => 'float:right;', 'checked' => 'checked')); ?>
            <?php echo $form->error($contentModel->parentContent(), 'published'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'company_name'); ?>
            <?php echo $form->textField($contentModel, 'company_name', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->error($contentModel, 'company_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel->parentContent(), 'code'); ?>
            <?php echo $form->textField($contentModel->parentContent(), 'code', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->error($contentModel->parentContent(), 'code'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel->parentContent(), 'currency'); ?>
            <?php echo $form->dropDownList($contentModel->parentContent(), 'currency', $this->getCurrencies(), array('options' => array($currency => array('label' => $currency, 'selected' => true)))); ?>
            <?php echo $form->error($contentModel->parentContent(), 'currency'); ?>
        </div>
    </fieldset>


    <?php $this->endWidget(); ?>

</div><!-- form -->