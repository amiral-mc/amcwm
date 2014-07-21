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
    <?php echo $form->errorSummary(array($contentModel)); ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <fieldset>
        <div class="row">
            <?php echo $form->labelEx($contentModel->parentContent(), 'published', array("style" => 'display:inline;')); ?>
            <?php echo $form->checkBox($contentModel->parentContent(), 'published'); ?>
            <?php echo $form->error($contentModel->parentContent(), 'published'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel->parentContent(), 'exchange_id'); ?>
            <?php echo $form->dropDownList($contentModel->parentContent(), 'exchange_id', CHtml::listData(Exchange::model()->findAll(array('order' => 'exchange_name DESC')), 'exchange_id', 'exchange_name')); ?>
            <?php echo $form->error($contentModel->parentContent(), 'exchange_id'); ?>
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
    </fieldset>        


    <?php $this->endWidget(); ?>

</div><!-- form -->