<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
                'id' => $formId,
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Expression data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'category_id'); ?>
            <?php echo $form->dropDownList($model, 'category_id', $model->getCategories(), array('prompt'=>  AmcWm::t('zii', 'Not set'))); ?>
            <?php echo $form->error($model, 'category_id'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'expression'); ?>
            <?php echo $form->textField($model, 'expression', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($model, 'expression'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'meaning'); ?>
            <?php echo $form->textField($contentModel, 'meaning', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'meaning'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'description'); ?>
            <?php echo $form->textArea($contentModel, 'description', array('maxlength' => 1024)); ?>
            <?php echo $form->error($contentModel, 'description'); ?>
        </div>
    </fieldset>        
    <?php $this->endWidget(); ?>

</div><!-- form -->