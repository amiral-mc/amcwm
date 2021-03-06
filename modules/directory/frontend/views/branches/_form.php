<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $model->company_id = $this->company->company_id;
    $form = $this->beginWidget('CActiveForm', array(
                'id' => $formId,
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
    
    echo Chtml::hiddenField('cid', $this->company->company_id);
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Branch data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'branch_name'); ?>
            <?php echo $form->textField($contentModel, 'branch_name', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($contentModel, 'branch_name'); ?>
        </div>
        <div>
            <?php echo $form->labelEx($model, 'country'); ?>
            <?php echo $form->dropDownList($model, 'country', $this->getCountries(true)); ?>
            <?php echo $form->error($model, 'country'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'branch_address'); ?>
            <?php echo $form->textField($contentModel, 'branch_address', array('size' => 60, 'maxlength' => 1024)); ?>
            <?php echo $form->error($contentModel, 'branch_address'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'city'); ?>
            <?php echo $form->textField($contentModel, 'city', array('size' => 60, 'maxlength' => 1024)); ?>
            <?php echo $form->error($contentModel, 'city'); ?>
        </div>
        <div>
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email'); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
        <div>
            <?php echo $form->labelEx($model, 'phone'); ?>
            <?php echo $form->textField($model, 'phone'); ?>
            <?php echo $form->error($model, 'phone'); ?>
        </div>
        <div>
            <?php echo $form->labelEx($model, 'mobile'); ?>
            <?php echo $form->textField($model, 'mobile'); ?>
            <?php echo $form->error($model, 'mobile'); ?>
        </div>
        <div>
            <?php echo $form->labelEx($model, 'fax'); ?>
            <?php echo $form->textField($model, 'fax'); ?>
            <?php echo $form->error($model, 'fax'); ?>
        </div>
    </fieldset>        
    <?php $this->endWidget(); ?>

</div><!-- form -->