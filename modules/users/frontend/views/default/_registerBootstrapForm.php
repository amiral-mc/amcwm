<div>
    <?php echo AmcWm::t("app", '_register_notes_') ?>
</div>
<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'register_form',
        'type' => 'horizontal',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <fieldset>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel, $model->users)); ?>
        <?php echo $form->textFieldRow($contentModel, 'name', array('size' => 35, 'maxlength' => 100)); ?>
        <?php
        echo $form->radioButtonListRow($model, 'sex', array(
            'm' => AmcWm::t("msgsbase.core", 'Male'),
            'f' => AmcWm::t("msgsbase.core", 'Female'),
        ));
        ?>
        <?php echo $form->dropDownListRow($model, 'country_code', $this->getCountries(), array('prompt' => AmcWm::t("msgsbase.core", 'Choose Country'))); ?>
        <?php echo $form->textFieldRow($model->users, 'username', array('size' => 35, 'maxlength' => 65)); ?>
        <?php echo $form->passwordFieldRow($model->users, 'passwd', array('size' => 35, 'maxlength' => 30)); ?>
        <?php echo $form->passwordFieldRow($model->users, 'passwdRepeat', array('size' => 35, 'maxlength' => 30)); ?>
        <?php echo $form->textFieldRow($model, 'email', array('size' => 35, 'maxlength' => 65)); ?>
        <?php echo $form->textFieldRow($model, 'emailRepeat', array('size' => 35, 'maxlength' => 65)); ?>
        <?php if ($enableSubscribe): ?>
            <?php echo $form->checkBoxRow($model, 'toMailList'); ?>
        <?php endif; ?>
        <?php if (CCaptcha::checkRequirements()): ?>
            <div>            
                <?php echo $form->labelEx($model->users, 'verifyCode'); ?>
                <div>
                    <?php $this->widget('CCaptcha', array('imageOptions' => array('height' => '45', 'border' => '0'), 'buttonType' => 'link')); ?>
                    <?php echo $form->textField($model->users, 'verifyCode'); ?>
                </div>
                <div class="hint"><?php echo AmcWm::t('amcFront', 'Please enter the letters as they are shown in the image above.') ?></div>
                <?php echo $form->error($model->users, 'verifyCode'); ?>
            </div>
        <?php endif; ?>        
        <div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => AmcWm::t("msgsbase.core", 'Register'))); ?>                        
        </div>
    </fieldset>    
    <?php $this->endWidget(); ?>
</div><!-- form -->