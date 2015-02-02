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
        <legend><?php echo AmcWm::t("msgsbase.core", "Documents category data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
        
        <div class="row">
            <?php echo $form->labelEx($model, 'parent_category'); ?>
            <?php echo $form->dropDownList($model, 'parent_category', DocsCategories::getCategoriesList(null, $model->category_id), array("prompt"=>"--")); ?>
            <?php echo $form->error($model, 'parent_category'); ?>
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
        
        <div class="row">
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>
    </fieldset>
    
    <fieldset>
        <?php
        $mediaSettings = AmcWm::app()->appModule->mediaSettings;
        $imagesInfo = $mediaSettings['categories']['path'];
        $drawImage = NULL;
        if ($model->category_id && $model->image_ext) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" .$imagesInfo . "/" . $model->category_id . "." . $model->image_ext))) {
                $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" .$imagesInfo . "/" . $model->category_id . "." . $model->image_ext . "?" . time(), "", array("class" => "image", "width" => "100")) . '</div>';
            }
        }
        ?>
        <legend><?php echo AmcWm::t("msgsbase.core", "Image Options"); ?>:</legend>       
        <div class="row">
            <?php echo $form->labelEx($model, 'imageFile'); ?>
            <?php echo $form->fileField($model, 'imageFile'); ?>
            <?php echo $form->error($model, 'imageFile'); ?>
        </div>

        <div id="itemImageFile">
            <?php echo $drawImage ?>
        </div>

        <?php if ($drawImage): ?>
            <div class="row">
                <input type="checkbox" name="deleteImage" id="deleteImage" style="float: right" onclick="deleteRelatedImage(this);" />
                <label for="deleteImage" id="lbldltimg" title=""><span><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                <label for="deleteImage" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='chklbl'><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
            </div>
            <?php
            Yii::app()->clientScript->registerScript('displayDeleteImage', "
                var imgDesc = null;
                deleteRelatedImage = function(chk){
                    if(chk.checked){
                        if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                            jQuery('#chklbl').text('" . CHtml::encode(AmcWm::t("amcBack", 'undo delete image')) . "');
                            imgDesc = jQuery('#imageDescription').val();
                            jQuery('#itemImageFile').slideUp();
                            jQuery('#imageDescription').val('');
                            jQuery('#lbldltimg').toggleClass('isChecked');
                        }else{
                            chk.checked = false;
                        }
                    }else{
                        jQuery('#chklbl').text('" . CHtml::encode(AmcWm::t("amcBack", 'Delete Image')) . "');
                        jQuery('#imageDescription').val(imgDesc);
                        jQuery('#itemImageFile').slideDown();
                        jQuery('#lbldltimg').toggleClass('isChecked');
                    }
                }    
            ", CClientScript::POS_HEAD);

            Yii::app()->clientScript->registerCss('displayImageCss', "
                label#lbldltimg span {
                    display: none;
                }
                #deleteImage{
                    display: none;
                }
                label#lbldltimg {
                    background:  url(" . $baseScript . "/images/remove.png) no-repeat;
                    width: 18px;
                    height: 18px;
                    display: block;
                    cursor: pointer;
                    float:right;
                    margin: 3px;
                }
                label#lbldltimg.isChecked {
                    background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                }
            ");
        endif;
        ?>

    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->