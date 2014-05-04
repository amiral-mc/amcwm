
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
    <?php echo Chtml::hiddenField('sid', $this->getParentId()); ?>
    <?php echo $form->errorSummary(array($model, $contentModel)); ?>
    <fieldset>                
        <legend><?php echo AmcWm::t("msgsbase.core", "Section data"); ?>:</legend>
        <div class="row">
            <span class="translated_label">
                <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
            </span>
            :
            <span class="translated_org_item">
                <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
            </span>
            <div class="row publish">
                <?php echo $form->checkbox($model, 'published'); ?>
                <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
                <?php echo $form->error($model, 'published'); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($contentModel, 'section_name'); ?>
                <?php echo $form->textField($contentModel, 'section_name', array('size' => 150, 'maxlength' => 150)); ?>
                <?php echo $form->error($contentModel, 'section_name'); ?>
            </div>
            <?php if ($model->parent_section): ?>
                <div class="row">
                    <?php echo $form->labelEx($model, 'parent_section'); ?>
                    <?php echo $form->dropDownList($model, 'parent_section', Sections::getSectionsList(null, $model->section_id)); ?>
                    <?php echo $form->error($model, 'parent_section'); ?>
                </div>
            <?php endif; ?>
            <?php if ($this->getModule()->appModule->useSupervisor): ?>

                <div class="row">
                    <?php echo $form->labelEx($contentModel, 'supervisor'); ?>
                    <?php echo $form->dropDownList($contentModel, 'supervisor', Persons::getSupervisorsList(AmcWm::t("msgsbase.core", "Without Supervisor"))); ?>
                    <?php echo $form->error($contentModel, 'supervisor'); ?>
                </div>   
            <?php endif; ?>
            <div class="row">
                <?php echo $form->labelEx($contentModel, 'description'); ?>
                <?php echo $form->error($contentModel, 'description'); ?>
                <?php
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                    'model' => $contentModel,
                    'attribute' => 'description',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:630px;'
                    ),
                        )
                );
                ?>            
            </div>      

    </fieldset>

    <fieldset>
        <?php
        $drawImage = NULL;
        if ($model->section_id && $model->image_ext) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageSizesInfo['topContent']['path'] . "/" . $model->section_id . "." . $model->image_ext))) {
                $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imageSizesInfo['topContent']['path'] . "/" . $model->section_id . "." . $model->image_ext . "?" . time(), "", array("class" => "image", "style" => "max-width:300px")) . '</div>';
            }
        }
        ?>
        <legend><?php echo AmcWm::t("amcBack", "imagefile"); ?>:</legend>       
        <div id="itemImageFile">
            <?php echo $drawImage ?>
        </div>
        <?php if ($drawImage): ?>
            <div class="row">
                <input type="checkbox" name="deleteImage" id="deleteImage" style="float: right" onclick="deleteRelatedImage(this);" />
                <label for="deleteImage" id="lbldltimg" title=""><span><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                <label for="deleteImage" title="" style='margin-top: 4px;cursor: pointer'><span id='chklbl'><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
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
        <div class="row">
            <?php echo $form->labelEx($model, 'imageFile'); ?>
            <?php echo $form->fileField($model, 'imageFile'); ?>
            <?php echo $form->error($model, 'imageFile'); ?>
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

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Section settings"); ?>:</legend>
        <div class="row">
            <span>
                <?php
                $settingsOptions = $model->getSettingsList();
                if (count($settingsOptions)) {
                    foreach ($settingsOptions as $optionType => $options) {
                        switch ($optionType) {
                            case 'radio':
                                foreach ($options as $optionKey => $optionValue) {
                                    echo CHtml::checkBox("{$model->getClassName()}[settingsOptions][{$optionType}][{$optionKey}]", $optionValue, array('id' => "settingsOptions_{$optionType}_{$optionKey}", 'class' => 'settingsOptions'));
                                    echo CHtml::label(AmcWm::t("msgsbase.core", "category_settings_{$optionType}_{$optionKey}_"), "settingsOptions_{$optionType}_{$optionKey}", array("class" => "checkbox_label"));
                                    echo "<br />";
                                }
                                break;
                        }
                    }
                }
                ?>
            </span>
            <?php echo $form->error($model, 'socialIds'); ?>
        </div>
        <script type="text/javascript">
            jQuery(function($) {
                $('.settingsOptions').click(function(){
                    var chkd = this.checked;
                    $(".settingsOptions").prop("checked", false);
                    this.checked = chkd;
                });
            });
        </script>
    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("amcBack", "Publish to the social media sites"); ?>:</legend>
        <div class="row">
            <div align="center"><?php echo AmcWm::t("msgsbase.core", 'Social Networks Hints'); ?></div>
            <?php echo $form->checkBoxList($model, 'socialIds', $this->getSocials(), array("separator" => "<br />", 'labelOptions' => array('class' => 'checkbox_label'))); ?>
            <?php echo $form->error($model, 'socialIds'); ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div><!-- form -->