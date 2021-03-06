<div class="form">
    <?php  
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => '<span class="required">*</span>')) ?>.</p>
    <?php echo $form->errorSummary(array($model, $contentModel)); ?>
    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "General Options"); ?>:</legend>
            <div class="row">                       
                <span class="translated_label">
                    <?php echo AmcWm::t("amcBack", "Content Language"); ?>
                </span>
                :
                <span class="translated_org_item">
                    <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
                </span>
            </div>              
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>            
            <?php echo $form->checkBox($model, 'show_gallery'); ?>
            <?php echo $form->labelEx($model, 'show_gallery', array("style" => 'display:inline;')); ?>            
        </fieldset> 
    </div>
    <fieldset>              
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'gallery_header'); ?>
            <?php echo $form->textField($contentModel, 'gallery_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($contentModel, 'gallery_header'); ?>
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
            <?php
            if ($model->isNewRecord) {
                $model->country_code = 'EG';
            }
            ?>
            <?php echo $form->labelEx($model, 'country_code'); ?>
            <?php echo $form->dropDownList($model, 'country_code', $this->getCountries(true)); ?>
            <?php echo $form->error($model, 'country_code'); ?>
        </div>        
    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("amcBack", "Tags"); ?>:</legend>
        <div class="row">
            <?php
            $this->widget('Keywards', array(
                'model' => $contentModel,
                'attribute' => "tags[]",
//                    'name' => "tags",
                'values' => $contentModel->tags,
                'formId' => $formId,
                'container' => "keywordItems",
                'delimiter' => Yii::app()->params["limits"]["delimiter"],
                'elements' => Yii::app()->params["limits"]["elements"], // keyword boxs count
                'wordsCount' => Yii::app()->params["limits"]["wordsCount"], //  words in each box count
                'htmlOptions' => array(),
                    )
            );
            ?>            
        </div>     
    </fieldset>

    <?php $this->endWidget(); ?>

</div><!-- form -->