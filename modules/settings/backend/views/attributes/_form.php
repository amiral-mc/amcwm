<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('Form', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Attribute Options"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
                
        
        <?php //echo $form->checkBox($model, 'is_new_type'); ?>
        <?php 
        $chkIsNew = $model->is_new_type;
        echo CHtml::checkBox('chkIsNewModel', $chkIsNew, array('onclick'=>"$('#isNewTypeName').toggle()")); 
        echo CHtml::label(AmcWm::t("msgsbase.core", "New Type"), 'chkIsNewModel', array("style" => 'display:inline;')); 
        ?>
        
        <div id="isNewTypeName" style="display: <?php echo ($chkIsNew)?'block':'none'?>;">
            <?php //echo $form->labelEx($model, 'is_new_type'); ?>
            <?php echo AmcWm::t("msgsbase.core", "Type the field name");?><br />
            <?php //echo $form->labelEx($model, 'is_new_type'); ?>
            <?php echo $form->textField($model, 'is_new_type', array('size' => 60, 'maxlength' => 30)); ?>
            <?php echo $form->error($model, 'is_new_type'); ?>
        </div>
        
        
        <div class="row">
            <?php echo $form->labelEx($model, 'module_id'); ?>
            <?php echo $form->dropDownList($model, 'module_id', SystemAttributes::getModulesList(), array('prompt' => AmcWm::t("msgsbase.core", 'Select Module'))); ?>
            <?php echo $form->error($model, 'module_id'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($model, 'attribute_type'); ?>
            <?php echo $form->dropDownList($model, 'attribute_type', SystemAttributes::getAttributesTypesList(), array('prompt' => AmcWm::t("msgsbase.core", 'Select Attribute Type'))); ?>
            <?php echo $form->error($model, 'attribute_type'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'label'); ?>
            <?php echo $form->textField($contentModel, 'label', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'label'); ?>
        </div>      
    </fieldset>        
    <?php $this->endWidget(); ?>

</div><!-- form -->