<div class="form">    
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $contentModel)); ?>
    <div>
        <fieldset>
            <legend><?php echo AmcWm::t("msgsbase.core", "General Options"); ?>:</legend>
            <div class="row">                       
                <span class="translated_label">
                    <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
                </span>
                :
                <span class="translated_org_item">
                    <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
                </span>
            </div>              
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>

            <?php echo $form->checkBox($model, 'in_slider'); ?>
            <?php echo $form->labelEx($model, 'in_slider', array("style" => 'display:inline;')); ?>         
        </fieldset>
    </div>
    <fieldset>   
        <legend><?php echo AmcWm::t("msgsbase.core", "Image Details"); ?>:</legend>       
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'image_header'); ?>
            <?php echo $form->textField($contentModel, 'image_header', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($contentModel, 'image_header'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'imageFile'); ?>
            <?php echo $form->fileField($model, 'imageFile'); ?>
            <input id="Images_imageFile_watermark" name="Images[imageFile_watermark]" type="checkbox" /> <?php echo AmcWm::t("amcBack", 'Use watermark');?>
            <?php echo $form->error($model, 'imageFile'); ?>
        </div>
        <?php
        $drawImage = null;
        if (!$model->isNewRecord) {
            $drawImage = Yii::app()->baseUrl . "/" . Yii::app()->getController()->imageInfo['path'] . "/" . $model->image_id . "." . $model->ext;
            $drawImage = str_replace("{gallery_id}", $model->gallery_id, $drawImage);
        }
        ?>
        <div class="row">
            <?php if ($drawImage): ?>
                <?php echo Chtml::image($drawImage, "", array("width" => 100)); ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'gallery_id'); ?>
            <?php echo $form->dropDownList($model, 'gallery_id', Galleries::getGalleriesList()); ?>
            <?php echo $form->error($model, 'gallery_id'); ?>
        </div>
        
        <?php if ($this->getModule()->appModule->useInfocus): ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'infocusId'); ?>
            <?php echo $form->dropDownList($model, 'infocusId', $this->getInfocus()); ?>
            <?php echo $form->error($model, 'infocusId'); ?>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'description'); ?>            
            <?php echo $form->textArea($contentModel, 'description'); ?>
            <?php echo $form->error($contentModel, 'description'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model, 'publish_date'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'publish_date',
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'class' => 'datebox',
                    'readonly' => 'readonly',
                    'value' => ($model->publish_date) ? Yii::app()->dateFormatter->format("y-MM-dd", $model->publish_date) : date("Y-m-d H:i"),
                )
            ));
            ?>
            <?php echo $form->error($model, 'publish_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'expire_date'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'expire_date',
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'class' => 'datebox',
                    'readonly' => 'readonly',
                    'value' => ($model->expire_date) ? Yii::app()->dateFormatter->format("y-MM-dd", $model->expire_date) : "",
                )
            ));
            ?>
            <?php echo Chtml::checkBox('no_expiry', ($model->expire_date) ? 0 : 1, array('onclick' => '$("#Images_expire_date").val("")')) ?>
            <?php echo Chtml::label(AmcWm::t("msgsbase.core", "No expiry date"), "remove_expiry", array("style" => 'display:inline;color:#3E4D57;font-weight:normal')) ?>
            <?php echo $form->error($model, 'expire_date'); ?>
        </div>       
    </fieldset>   

    <?php if ($this->getModule()->appModule->useKeywords): ?>
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
                'wordsCount' => Yii::app()->params["limits"]["wordsCount"], // words in each box count
                'htmlOptions' => array(),
                    )
            );
            ?>            
        </div>     
    </fieldset>
    <?php endif; ?>
    
    <?php if ($this->getModule()->appModule->useSocials): ?>
    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("amcBack", "Publish to the social media sites"); ?>:</legend>
            <?php echo $form->checkBoxList($model, 'socialIds', $this->getSocials(), array("separator" => "<br />", 'labelOptions' => array('class' => 'checkbox_label'))); ?>
            <?php echo $form->error($model, 'socialIds'); ?>
        </fieldset>
    </div>
    <?php endif; ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->