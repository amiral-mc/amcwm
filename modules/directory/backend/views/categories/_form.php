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
        <legend><?php echo AmcWm::t("msgsbase.core", "Directory category data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
        <div class="row">
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'category_name'); ?>
            <?php echo $form->textField($contentModel, 'category_name', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($contentModel, 'category_name'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'category_description'); ?>
            <?php echo $form->error($contentModel, 'category_description'); ?>
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
        </div>
        <div>
            <legend><?php echo AmcWm::t("msgsbase.core", "Category settings"); ?>:</legend>

            <div class="row">
                <span>                    
                    <?php
                    $settingsOptions = $model->getSettingsList();
                    foreach ($settingsOptions as $optionType => $options) {
                        switch ($optionType) {
                            case 'check':
                                foreach ($options as $optionKey => $optionValue) {
                                    echo CHtml::checkBox("{$model->getClassName()}[settingsOptions][{$optionType}][{$optionKey}]", $optionValue, array('id'=>"settingsOptions_{$optionType}_{$optionKey}"));
                                    echo CHtml::label(AmcWm::t("msgsbase.core", "category_settings_{$optionType}_{$optionKey}_"), "settingsOptions_{$optionType}_{$optionKey}", array("class"=>"checkbox_label"));
                                    echo "<br />";
                                }
                                break;
                        }
                    }
                    ?>
                </span>
                <?php echo $form->error($model, 'socialIds'); ?>
            </div>
        </div>

    </fieldset>        


    <?php $this->endWidget(); ?>

</div><!-- form -->