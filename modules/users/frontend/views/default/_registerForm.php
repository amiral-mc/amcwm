<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'register_form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2">
                <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
                <?php echo $form->errorSummary(array($model, $contentModel, $model->users)); ?>
            </td>        
        </tr>
        <tr>
            <td>
                <div class="form_item_label">                    
                    <?php echo $form->labelEx($contentModel, 'name'); ?>
                </div>
                <div>
                    <?php echo $form->textField($contentModel, 'name', array('size' => 35, 'maxlength' => 100)); ?>
                    <?php echo $form->error($contentModel, 'name'); ?>
                </div>

                <div class="form_item_label">                    
                    <?php $sexLabels = AmcWm::t("msgsbase.core", 'sexLabels'); ?>
                    <?php echo $form->labelEx($model, 'sex'); ?>                        
                </div>
                <div>
                    <?php echo $form->radioButton($model, 'sex', array("uncheckValue" => null, 'value' => 'm')); ?>            
                    <span class="op_item"><?php echo $sexLabels['m']; ?></span>
                    <?php echo $form->radioButton($model, 'sex', array("uncheckValue" => null, 'value' => 'f')); ?>
                    <span class="op_item"><?php echo $sexLabels['f']; ?></span>
                    <?php echo $form->error($model, 'sex'); ?>        
                </div>
                <div class="form_item_label">                                       
                    <?php echo $form->labelEx($model, 'country_code'); ?>
                </div>
                <div>
                    <?php echo $form->dropDownList($model, 'country_code', $this->getCountries(), array('prompt' => AmcWm::t("msgsbase.core", 'Choose Country'))); ?>
                    <?php echo $form->error($model, 'country_code'); ?>
                </div>
                <?php if (CCaptcha::checkRequirements()): ?>
                    <div class="form_item_label">            
                        <?php echo $form->labelEx($model->users, 'verifyCode'); ?>
                        <div>
                            <?php $this->widget('CCaptcha', array('imageOptions' => array('height' => '45', 'border' => '0'), 'buttonType' => 'link')); ?>
                            <?php echo $form->textField($model->users, 'verifyCode'); ?>
                        </div>
                        <div class="hint"><?php echo AmcWm::t('amcFront', 'Please enter the letters as they are shown in the image above.') ?></div>
                        <?php echo $form->error($model->users, 'verifyCode'); ?>
                    </div>
                <?php endif; ?>
            </td>


            <td>
                <div class="form_item_label">                                       
                    <?php echo $form->labelEx($model->users, 'username'); ?>                       
                </div>
                <div>
                    <?php echo $form->textField($model->users, 'username', array('size' => 35, 'maxlength' => 65)); ?>
                    <?php echo $form->error($model->users, 'username'); ?>
                </div>
                <div class="form_item_label">                                       
                    <?php echo $form->labelEx($model->users, 'passwd'); ?>                       
                </div>
                <div>
                    <?php echo $form->passwordField($model->users, 'passwd', array('size' => 35, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model->users, 'passwd'); ?>
                </div>
                <div class="form_item_label">                                       
                    <?php echo $form->labelEx($model->users, 'passwdRepeat'); ?>                       
                </div>
                <div>
                    <?php echo $form->passwordField($model->users, 'passwdRepeat', array('size' => 35, 'maxlength' => 30)); ?>
                    <?php echo $form->error($model->users, 'passwdRepeat'); ?>
                </div>
                <div class="form_item_label">                                       
                    <?php echo $form->labelEx($model, 'email'); ?>                       
                </div>
                <div>
                    <?php echo $form->textField($model, 'email', array('size' => 35, 'maxlength' => 65)); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>
                <div class="form_item_label">                                       
                    <?php echo $form->labelEx($model, 'emailRepeat'); ?>                       
                </div>
                <div>
                    <?php echo $form->textField($model, 'emailRepeat', array('size' => 35, 'maxlength' => 65)); ?>
                    <?php echo $form->error($model, 'emailRepeat'); ?>
                </div>                									
            </td>
        </tr>
        <?php if ($enableSubscribe): ?>
            <tr>
                <td colspan="2">
                    <div class="row">
                        <?php
                        echo $form->checkBox($model, 'toMailList');
                        echo $form->labelEx($model, 'toMailList', array("style" => 'display:inline;'));
                        ?>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td colspan="2">
                <div class="row">
                    <?php echo AmcWm::t("app", '_register_notes_') ?>
                </div>
            </td>
        </tr>
        <tr colspan="2">
            <td><?php echo CHtml::submitButton(AmcWm::t("msgsbase.core", 'Register')); ?></td>
        </tr>							
    </table>	
    <?php $this->endWidget(); ?>
</div><!-- form -->