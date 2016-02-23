<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'unsubscribe_form',
        'action' => array("/maillist/default/unsubscribe", 'lang'=>AmcWm::app()->language),
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <table cellspacing="0" cellpadding="0">

        <tr>
            <td>
                <div class="form_item_label">                                       
                    <?php echo $form->labelEx($model->maillistUsers, 'email'); ?>                       
                </div>
                <div>
                    <?php echo $form->textField($model->maillistUsers, 'email', array('size' => 35, 'maxlength' => 65)); ?>
                    <?php echo $form->error($model->maillistUsers, 'email'); ?>
                </div>
                <div class="form_item_label">                                       
                    <?php echo $form->labelEx($model->maillistUsers, 'emailRepeat'); ?>                       
                </div>
                <div>
                    <?php echo $form->textField($model->maillistUsers, 'emailRepeat', array('size' => 35, 'maxlength' => 65)); ?>
                    <?php echo $form->error($model->maillistUsers, 'emailRepeat'); ?>
                </div>                									
            </td>
            <td>
                <?php if (CCaptcha::checkRequirements()): ?>
                    <div class="form_item_label">            
                        <?php echo $form->labelEx($model->maillistUsers, 'verifyCode'); ?>
                        <div>
                            <?php $this->widget('CCaptcha', array('imageOptions' => array('height' => '45', 'border' => '0'), 'buttonType' => 'link')); ?>
                            <?php echo $form->textField($model->maillistUsers, 'verifyCode'); ?>
                        </div>
                        <div class="hint"><?php echo AmcWm::t('amcFront', 'Please enter the letters as they are shown in the image above.') ?></div>
                        <?php echo $form->error($model->maillistUsers, 'verifyCode'); ?>
                    </div>
                <?php endif; ?>
            </td>

        </tr>
        <tr colspan="2">
            <td style="padding-top: 10px;"><?php echo CHtml::submitButton(AmcWm::t("msgsbase.core", 'Confirm')); ?></td>
        </tr>							
    </table>	
    <?php $this->endWidget(); ?>
</div><!-- form -->