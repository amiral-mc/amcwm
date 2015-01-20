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
        <legend><?php echo AmcWm::t("msgsbase.core", "Jobs categories data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'category_name'); ?>
            <?php echo $form->textField($contentModel, 'category_name', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($contentModel, 'category_name'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'category_description'); ?>
            <?php
            $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                'model' => $contentModel,
                'attribute' => 'category_description',
                'editorTemplate' => 'full',
                'htmlOptions' => array(
                    'style' => 'height:300px; width:630px;'
                ),
                    )
            );
            ?>
            <?php echo $form->error($contentModel, 'category_description'); ?>
        </div>
        <div>
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>
    </fieldset>        
    <?php $this->endWidget(); ?>

</div><!-- form -->