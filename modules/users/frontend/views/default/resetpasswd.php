<?php $this->beginClip('form'); ?>
<?php if (AmcWm::app()->frontend['bootstrap']['use']): ?>


    <div class="form">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'forget_form',
            'type' => 'horizontal',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        ?>
        <fieldset>
            <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
            <?php echo $form->errorSummary($model); ?>
            <?php echo $form->textFieldRow($model, 'email', array('size' => 35, 'maxlength' => 65)); ?>
            <?php echo $form->passwordFieldRow($model, 'passwd', array('size' => 35, 'maxlength' => 30)); ?>
            <?php echo $form->passwordFieldRow($model, 'passwdRepeat', array('size' => 35, 'maxlength' => 30)); ?>        
            <?php if (CCaptcha::checkRequirements()): ?>
                <div>            
                    <?php echo $form->labelEx($model, 'verifyCode'); ?>
                    <div>
                        <?php $this->widget('CCaptcha', array('imageOptions' => array('height' => '45', 'border' => '0'), 'buttonType' => 'link')); ?>
                        <?php echo $form->textField($model, 'verifyCode'); ?>
                    </div>
                    <div class="hint"><?php echo AmcWm::t("amcFront", 'Please enter the letters as they are shown in the image above.') ?></div>
                    <?php echo $form->error($model, 'verifyCode'); ?>
                </div>
            <?php endif; ?>        
            <div class="form-actions">
                <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => AmcWm::t("msgsbase.core", 'Change password'))); ?>                        
            </div>
        </fieldset>    
        <?php $this->endWidget(); ?>
    </div><!-- form -->

<?php else: ?>


    <div class="form">
        <?php echo CHtml::errorSummary($model); ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'register_form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        ?>

        <div class="form_item_label">
            <div class="form_item_label">
                <?php echo $form->labelEx($model, 'email'); ?>                       
            </div>
            <div>
                <?php echo $form->textField($model, 'email', array('size' => 35, 'maxlength' => 65)); ?>
                <?php echo $form->error($model, 'email'); ?>
            </div>

            <?php echo $form->labelEx($model, 'passwd'); ?>                       
        </div>
        <div>
            <?php echo $form->passwordField($model, 'passwd', array('size' => 35, 'maxlength' => 30)); ?>
            <?php echo $form->error($model, 'passwd'); ?>
        </div>
        <div class="form_item_label">                                       
            <?php echo $form->labelEx($model, 'passwdRepeat'); ?>                       
        </div>
        <div>
            <?php echo $form->passwordField($model, 'passwdRepeat', array('size' => 35, 'maxlength' => 30)); ?>
            <?php echo $form->error($model, 'passwdRepeat'); ?>
        </div>

        <?php if (CCaptcha::checkRequirements()): ?>
            <div class="form_item_label">            
                <?php echo $form->labelEx($model, 'verifyCode'); ?>
                <div>
                    <?php $this->widget('CCaptcha', array('imageOptions' => array('height' => '45', 'border' => '0'), 'buttonType' => 'link')); ?>
                    <?php echo $form->textField($model, 'verifyCode'); ?>
                </div>
                <div class="hint"><?php echo AmcWm::t("amcFront", 'Please enter the letters as they are shown in the image above.') ?></div>
                <?php echo $form->error($model, 'verifyCode'); ?>
            </div>
        <?php endif; ?>

        <div> <?php echo CHtml::submitButton(AmcWm::t("msgsbase.core", 'Change password')); ?> </div>
        <?php $this->endWidget(); ?>
    </div><!-- form -->

<?php endif; ?>
<?php $this->endClip('form'); ?>

<?php
$breadcrumbs[] = AmcWm::t("msgsbase.core", "Forget password");
$this->widget('PageContentWidget', array(
    'id' => 'forgot-passwd',
    'contentData' => $this->clips['form'],
    'title' => AmcWm::t("msgsbase.core", 'Recover your password'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));

