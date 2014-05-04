<div class="form">
    <?php
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
                'id' => $formId,
                'enableAjaxValidation' => false,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
                'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Department data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
        
        <div class="row">
            <?php echo $form->labelEx($model, 'parent_department'); ?>
            <?php echo $form->dropDownList($model, 'parent_department', TendersDepartment::model()->getDepartmentsList($model->department_id), array("prompt"=>"--")); ?>
            <?php echo $form->error($model, 'parent_department'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'department_name'); ?>
            <?php echo $form->textField($contentModel, 'department_name', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'department_name'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>
    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->