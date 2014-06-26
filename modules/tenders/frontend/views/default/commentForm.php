<div class="form">    
    <?php    
    $form = $this->beginWidget('CActiveForm', array(
                'id' => $formId,
                'enableAjaxValidation' => false,
                'action' => $action,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                )
            ));
    ?>
    <p class="note"><?php echo AmcWm::t('msgsbase.core', "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model->commentsOwners, $model)); ?>
    <?php if(Yii::app()->request->getParam("page")):?>
    <?php echo CHtml::hiddenField('page', Yii::app()->request->getParam("page"), array('id'=>"{$formId}_page"))?>    
    <?php endif;?>
    <table cellspacing="2" cellpadding="2" width="100%">
        <tr>
            <td class="form_comment_label"><?php echo $form->labelEx($model->commentsOwners, 'name'); ?></td>
            <td>
                <?php echo $form->textField($model->commentsOwners, 'name', array('size' => 40, 'maxlength' => 40)); ?>
                <?php echo $form->error($model->commentsOwners, 'name'); ?>
            </td>
        </tr>
        <tr>
            <td class="form_comment_label"><?php echo $form->labelEx($model->commentsOwners, 'email'); ?></td>
            <td>
                <?php echo $form->textField($model->commentsOwners, 'email', array('size' => 40, 'maxlength' => 100)); ?>
                <?php echo $form->error($model->commentsOwners, 'email'); ?>
            </td>
        </tr>
        <tr>
            <td class="form_comment_label"><?php echo $form->labelEx($model, 'comment_header'); ?></td>
            <td>
                <?php echo $form->textField($model, 'comment_header', array('size' => 40, 'maxlength' => 50)); ?>
                <?php echo $form->error($model, 'comment_header'); ?>
            </td>
        </tr>
        <tr>
            <td class="form_comment_label"><?php echo $form->labelEx($model, 'comment'); ?></td>
            <td>
                <?php echo $form->textArea($model, 'comment', array("rows"=>"10", "cols"=>"50")); ?>
                <?php echo $form->error($model, 'comment'); ?>
            </td>
        </tr>
        <tr>
            <td class="form_comment_label"><?php echo $form->labelEx($model, 'verifyCode'); ?></td>
            <td>
                <?php if (CCaptcha::checkRequirements()): ?>
                    <div>
                        <?php echo $form->textField($model, 'verifyCode'); ?>
                        <?php $this->widget('CCaptcha'); ?>
                    </div>
                    <div class="hint"><?php echo AmcWm::t('amcFront', 'Please enter the letters as they are shown in the image above.') ?></div>
                    <?php echo $form->error($model, 'verifyCode'); ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <?php echo CHtml::submitButton(AmcWm::t('msgsbase.core', 'Add Comment'), array("id"=>"{$formId}_submit")); ?>
            </td>
        </tr>
    </table>
    <input type="hidden" id="<?php echo $formId?>_commentId" name="commentId" value="" />
    <?php $this->endWidget(); ?>
</div>
