<div class="form">    
    <?php
    $editBody = true;
    if ($this->channel && $this->channel->auto_generate) {
        $editBody = false;
    }
    $form = $this->beginWidget('Form', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    $templates = Maillist::getTemplatesList($this->channel);
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary($model); ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.mailing", "Email message data"); ?>:</legend>
        <div class="row">
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>
        <?php if (count($templates['list'])): ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'template_id'); ?>
                <?php echo $form->dropDownList($model, 'template_id', $templates['list'], array('empty' => Yii::t('zii', 'Not set'), 'onchange' => 'updateMessageData.update(this.value)')); ?>
                <?php echo $form->error($model, 'template_id'); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'subject'); ?>
            <?php echo $form->textField($model, 'subject', array('size' => 45, 'maxlength' => 145, 'style' => 'direction:ltr')); ?>
            <?php echo $form->error($model, 'subject'); ?>
        </div>
        <?php if ($editBody): ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'body'); ?>            
                <?php echo $form->richTextField($model, 'body', array('height' => '400px')); ?>
                <?php echo $form->error($model, 'body'); ?>
            </div>        
        <?php endif; ?>
        <?php if(isset($model->channel->auto_generate) && $model->channel->auto_generate):?>
        <div class="row">                       
            <?php echo $form->labelEx($model, 'section_id', array('label' => AmcWm::t("msgsbase.channels", "Section"))); ?>
            <?php echo $form->dropDownList($model, 'sectionsIds', Sections::getSectionsList(), array('empty' => Yii::t('zii', 'Not set'), 'multiple' => 'multiple', 'style' => 'height:100px;')); ?>
            <?php echo $form->error($model, 'section_id'); ?>
        </div>
        <?php endif;?>
            <div class="row">
                <?php echo $form->labelEx($model, 'cron_condition'); ?>
                <?php echo $form->textField($model, 'cron_step', array('size' => 2, 'maxlength' => 10, 'style' => 'width:20px;height:10px;text-align:center')); ?>
                <?php echo $form->dropDownList($model, 'cron_condition', MaillistMessage::cronConditionsList(), array('empty' => Yii::t('zii', 'Not set'), 'onchange' => 'conditionAction.changeCondition(this.value);')); ?>
                <?php echo $form->error($model, 'cron_condition'); ?>
            </div>        
            <div class="row">
                <?php echo $form->labelEx($model, 'cron_start'); ?>
                <?php
                $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                    'model' => $model,
                    'attribute' => 'cron_start',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                        'timeFormat' => 'hh:mm',
                        'changeMonth' => true,
                        'changeYear' => false,
                    ),
                    'htmlOptions' => array(
                        'class' => 'datebox',
                        'style' => 'direction:ltr',
                        'readonly' => 'readonly',
                    )
                ));
                ?>
                <?php echo CHtml::button(AmcWm::t('msgsbase.core', 'Start At The Moment'), array('onclick' => '$("#MaillistMessage_cron_start").val("'.date("Y-m-d H:i").'")'));?>
                <?php echo $form->error($model, 'cron_start'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model, 'cron_end'); ?>
                <?php
                $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                    'model' => $model,
                    'attribute' => 'cron_end',
                    'options' => array(
                        'showAnim' => 'fold',
                        'dateFormat' => 'yy-mm-dd',
                        'timeFormat' => 'hh:mm',
                        'changeMonth' => true,
                        'changeYear' => false,
                        'onSelect' => "js:function(dateText, inst){\$('#MaillistMessage_withoutCronEnd').attr('checked', false);}"
                    ),
                    'htmlOptions' => array(
                        'class' => 'datebox',
                        'style' => 'direction:ltr',
                        'readonly' => 'readonly',
                    )
                ));
                ?>
                <?php
                echo $form->checkBox($model, 'withoutCronEnd', array('onclick' => '$("#MaillistMessage_cron_end").val("")'));
                echo $form->labelEx($model, 'withoutCronEnd', array("style" => 'display:inline;'));
                ?>
                <?php echo $form->error($model, 'cron_end'); ?>
            </div>
    </fieldset>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
Yii::app()->clientScript->registerScript('updateMessageData', "
    conditionAction = {};
    conditionAction.changeCondition = function (cond){        
        if(!cond){          
                $('#MaillistMessage_cron_step').val('');
                $('#MaillistMessage_cron_end').val('');
        }
    }    
    updateMessageData = {};
    updateMessageData.templates = " . CJSON::encode($templates['data']) . ";
    updateMessageData.update = function (templateId){
        if(typeof updateMessageData.templates[templateId] != 'undefined'){
           if(updateMessageData.templates[templateId].subject){
                $('#MaillistMessage_subject').val(updateMessageData.templates[templateId].subject);
           }
           if(updateMessageData.templates[templateId].msg){
                tinyMCE.activeEditor.setContent(updateMessageData.templates[templateId].msg);
           }
           
        }
    }    
");
?>