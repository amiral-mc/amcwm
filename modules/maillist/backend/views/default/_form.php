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

    <?php echo $form->errorSummary($model); ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Email data"); ?>:</legend>
        
        <?php /*if ($model->isNewRecord): ?>
            - <?php echo AmcWm::t("msgsbase.core", "You can add one or more email each email must be in a new line (press enter after each email)"); ?>
            <br />- <?php echo AmcWm::t("msgsbase.core", "Repeated emails will be ignored"); ?>
            <br />- <?php echo AmcWm::t("msgsbase.core", "Emails that fail in validation will be ignored"); ?>

        <div class="row">
            <?php echo $form->labelEx($model->maillistUsers, 'email'); ?>
            <?php echo $form->textArea($model->maillistUsers, 'email', array('cols' => 45, 'rows' => 50, 'style' => 'direction:ltr')); ?>
            <?php echo $form->error($model->maillistUsers, 'email'); ?>
        </div> 
         
        <?php endif; */?>

        <div class="row">
            <?php echo $form->labelEx($model->maillistUsers, 'name'); ?>
            <?php echo $form->textField($model->maillistUsers, 'name', array('size' => 45, 'maxlength' => 145)); ?>
            <?php echo $form->error($model->maillistUsers, 'name'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($model->maillistUsers, 'email'); ?>
            <?php echo $form->textField($model->maillistUsers, 'email', array('size' => 45, 'maxlength' => 145, 'style' => 'direction:ltr')); ?>
            <?php echo $form->error($model->maillistUsers, 'email'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php echo $form->checkbox($model, 'status'); ?>
            <?php echo $form->error($model, 'status'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div><!-- form -->