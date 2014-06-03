
<div class="form">
    <?php
    $imageSizesInfo = $this->getModule()->appModule->mediaPaths;
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
    ));
    ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam()); ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $contentModel)); ?>
    <fieldset>                
        <legend><?php echo AmcWm::t("msgsbase.sources", "Source data"); ?>:</legend>
        <div class="row">
            <span class="translated_label">
                <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
            </span>
            :
            <span class="translated_org_item">
                <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
            </span>                      
            <div class="row">
                <?php echo $form->labelEx($contentModel, 'source'); ?>
                <?php echo $form->textField($contentModel, 'source', array('size' => 100, 'maxlength' => 100)); ?>
                <?php echo $form->error($contentModel, 'source'); ?>
            </div>
              <div class="row">
                <?php echo $form->labelEx($model, 'url'); ?>
                <?php echo $form->textField($model, 'url', array('size' => 255, 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'url'); ?>
            </div>


    </fieldset>   
    <?php $this->endWidget(); ?>

</div><!-- form -->