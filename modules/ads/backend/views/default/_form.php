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
    <?php echo $form->errorSummary(array($model, $model)); ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <fieldset>                
        <div class="row">
            <?php echo $form->labelEx($model, 'server_id'); ?>
            <?php echo $form->dropDownList($model, 'server_id', CHtml::listData(AdsServersConfig::model()->findAll(array('order' => 'server_name ASC')), 'server_id', 'server_name')); ?>
            <?php echo $form->error($model, 'server_id'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'zone_id'); ?>
            <?php echo $form->dropDownList($model, 'zone_id', CHtml::listData(DefaultAdsZones::model()->findAll(array('order' => 'zone_name ASC')), 'zone_id', 'zone_name')); ?>
            <?php echo $form->error($model, 'zone_id'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'invocation_code'); ?>
            <?php echo $form->textArea($model, 'invocation_code', array('dir' => 'ltr')); ?>
            <?php echo $form->error($model, 'invocation_code'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'published'); ?>
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->error($model, 'published'); ?>
        </div>
    </fieldset>


    <?php $this->endWidget(); ?>

</div><!-- form -->