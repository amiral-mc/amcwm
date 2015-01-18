<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('Form', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
    ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "General Option"); ?>:</legend>
        <div class="row">
            <span class="translated_label">
                <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
            </span>
            :
            <span class="translated_org_item">
                <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
            </span>
        </div>
        <?php
        echo $form->checkBox($model, 'published');
        echo $form->labelEx($model, 'published', array("style" => 'display:inline;'));
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'product_code'); ?>
            <?php echo $form->textField($model, 'product_code', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($model, 'product_code'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'price'); ?>
            <?php echo $form->textField($model, 'price', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($model, 'price'); ?>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Details"); ?>:</legend>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'product_name'); ?>
            <?php echo $form->textField($contentModel, 'product_name', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($contentModel, 'product_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'product_description'); ?>
            <?php echo $form->error($contentModel, 'product_description'); ?>
            <?php echo $form->richTextField($contentModel, 'product_description', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'product_brief'); ?>
            <?php echo $form->error($contentModel, 'product_brief'); ?>
            <?php echo $form->richTextField($contentModel, 'product_brief', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'product_specifications'); ?>
            <?php echo $form->error($contentModel, 'product_specifications'); ?>
            <?php echo $form->richTextField($contentModel, 'product_specifications', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'section_id'); ?>
            <?php
            $this->widget('amcwm.core.widgets.select2.ESelect2', array(
                'model' => $model,
                'attribute' => "section_id",
                'useSelect' => true,
                'data' => Sections::getSectionsList(),
                'options' => array(
                    "dropdownCssClass" => "bigdrop",
                    "placeholder" => AmcWm::t('amcTools', 'Enter Search Keywords'),
                ),
                'htmlOptions' => array(
                    'style' => 'style="width:80%"',
                ),
            ));
            ?>
            <?php echo $form->error($model, 'section_id'); ?>
        </div>                 
        <div class="row">
            <?php echo $form->labelEx($model, 'publish_date'); ?>
            <?php echo $form->calendarField($model, 'publish_date', array('class' => 'datebox', 'dateOptions' => array("dateOnly" => 0))); ?>           
            <?php echo $form->error($model, 'publish_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'expire_date'); ?>
            <?php echo $form->calendarField($model, 'expire_date', array('class' => 'datebox', 'dateOptions' => array("dateOnly" => 0))); ?>
            <?php echo $form->error($model, 'expire_date'); ?>
        </div>
    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("amcBack", "Tags"); ?>:</legend>
        <div class="row">
            <?php
            $this->widget('Keywards', array(
                'model' => $contentModel,
                'attribute' => "tags[]",
                'values' => $contentModel->tags,
                'formId' => $formId,
                'container' => "keywordItems",
                'delimiter' => Yii::app()->params["limits"]["delimiter"],
                'elements' => Yii::app()->params["limits"]["elements"], // keyword boxs count
                'wordsCount' => Yii::app()->params["limits"]["wordsCount"], // words in each box count
                'htmlOptions' => array(),
                    )
            );
            ?>            
        </div>     
    </fieldset>
    <?php $this->endWidget(); ?>
</div>