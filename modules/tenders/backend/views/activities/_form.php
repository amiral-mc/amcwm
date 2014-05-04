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
            ));
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Activity data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'activity_name'); ?>
            <?php echo $form->textField($contentModel, 'activity_name', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($contentModel, 'activity_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>
    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->