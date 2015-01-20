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
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam()); ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Supervisor data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
        <div class="row">

            <span class="translated_label">
                <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
            </span>
            :
            <span class="translated_org_item">
                <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
            </span>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'name'); ?>
            <?php echo $form->textField($contentModel, 'name', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($contentModel, 'name'); ?>
        </div>
        <div class="row">
            <?php $sexLabels = AmcWm::t("msgsbase.core", 'sexLabels'); ?>
            <?php echo $form->labelEx($model, 'sex'); ?>                        
            <?php echo $sexLabels['m']; ?>
            <?php echo $form->radioButton($model, 'sex', array("uncheckValue" => null, 'value' => 'm')); ?>            
            <?php echo $sexLabels['f']; ?>
            <?php echo $form->radioButton($model, 'sex', array("uncheckValue" => null, 'value' => 'f')); ?>
            <?php echo $form->error($model, 'sex'); ?>
        </div>        

        <div class="row">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
        <div class="row">
            <?php
            if ($model->isNewRecord) {
                $model->country_code = 'EG';
            }
            ?>
            <?php echo $form->labelEx($model, 'country_code'); ?>
            <?php echo $form->dropDownList($model, 'country_code', $this->getCountries()); ?>
            <?php echo $form->error($model, 'country_code'); ?>
        </div>        

    </fieldset>            

    <?php $this->endWidget(); ?>

</div><!-- form -->