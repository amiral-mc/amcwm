<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary($model); ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.channels", "Channel data"); ?>:</legend>
        <div class="row">
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>       
        <div class="row">
            <?php echo $form->labelEx($model, 'channel'); ?>
            <?php echo $form->textField($model, 'channel', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'channel'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'content_lang'); ?>
            <?php echo $form->dropDownList($model, 'content_lang', array('ar' => AmcWm::t("msgsbase.channels", "Arabic"), 'en' => AmcWm::t("msgsbase.channels", "English"))); ?>
            <?php echo $form->error($model, 'content_lang'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('changeCronCondition', "
    conditionAction = {};
        conditionAction.changeCondition = function (cond){        
        if(!cond){          
            $('#MaillistChannels_cron_step').val('');
            $('#MaillistChannels_cron_end').val('');
        }
    }    
");
?>