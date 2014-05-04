<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'unsubscribe_form',
        'type' => 'horizontal',
        'inlineErrors' => true,
        'action' => array("/maillist/default/unsubscribe"),
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <fieldset>
        <?php echo $form->textFieldRow($model->maillistUsers, 'email'); ?>                   
        <?php echo $form->textFieldRow($model->maillistUsers, 'emailRepeat'); ?>                   
        
        <?php if (CCaptcha::checkRequirements()): ?>        
            <?php echo $form->labelEx($model->maillistUsers, 'verifyCode'); ?>
            <?php $this->widget('CCaptcha', array('imageOptions' => array('height' => '45', 'border' => '0'), 'buttonType' => 'link')); ?>        
            <?php echo $form->textField($model->maillistUsers, 'verifyCode'); ?>
        <?php endif; ?>
   <div class="form-actions">
                    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' =>AmcWm::t("msgsbase.core", 'Confirm'))); ?>                        
                </div>
        
    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->