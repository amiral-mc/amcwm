<div class="form">
    <?php
    $module = $this->module->appModule->currentVirtual;
    $options = $this->module->appModule->options;
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    $imagesInfo = $this->getModule()->appModule->mediaPaths;
    $model = $contentModel->getParentContent();
    /**
     * @todo fix the client side validation in the tinyMCE editor.
     */
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

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam()); ?>
    <?php echo $form->errorSummary(array_merge(array($model, $contentModel, $model->news->getCurrent()), $contentModel->titles)); ?>
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
        <?php echo $form->checkBox($model, 'published'); ?>
        <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>            
        <?php echo $form->checkBox($model, 'archive'); ?>
        <?php echo $form->labelEx($model, 'archive', array("style" => 'display:inline;')); ?>
        <?php echo $form->checkBox($model, 'in_ticker'); ?>
        <?php echo $form->labelEx($model, 'in_ticker', array("style" => 'display:inline;')); ?>                       
        <?php echo $form->checkBox($model, 'in_list'); ?>
        <?php echo $form->labelEx($model, 'in_list', array("style" => 'display:inline;')); ?>
        <?php //echo $form->checkBox($model, 'in_spot'); ?>
        <?php //echo $form->labelEx($model, 'in_spot', array("style" => 'display:inline;')); ?>                               
        <div style="padding-top:5px;padding-bottom: 5px;">            
            <?php if ($options['default']['check']['addToSlider']): ?>
                <?php echo $form->checkBox($model, 'in_slider', array('value' => ($model->in_slider) ? $model->in_slider : null)); ?>       
                <?php echo $form->labelEx($model, 'in_slider', array("style" => 'display:inline;')); ?>            
            <?php endif; ?>
            <?php if ($options[$module]['default']['check']['addToBreaking']): ?>                
                <?php                
                if (!$model->isNewRecord && $options[$module]['default']['integer']['breakingExpiredAfter'] <= time() - strtotime($model->publish_date)) {
                    echo $form->labelEx($model->news, 'is_breaking', array("style" => 'display:inline;')) . ": ";
                    if ($model->news->is_breaking) {
                        echo AmcWm::t("amcFront", "Yes");
                    } else {
                        echo AmcWm::t("amcFront", "No");
                    }
                    
                } else {
                    echo $form->checkBox($model->news, 'is_breaking');
                    echo $form->labelEx($model->news, 'is_breaking', array("style" => 'display:inline;'));
                }
                ?>
            <?php endif; ?>

        </div>
        <?php if ($options['default']['check']['addToSlider']): ?>
            <?php
            $sliderUploadDisplay = ($model->in_slider) ? "block" : "none";
            $drawSliderImage = NULL;
            if ($model->article_id && $model->in_slider) {
                if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['slider']['path'] . "/" . $model->article_id . "." . $model->in_slider))) {
                    $drawSliderImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['slider']['path'] . "/" . $model->article_id . "." . $model->in_slider . "?" . time(), "", array("class" => "image", "width" => "200")) . '</div>';
                }
            }
            ?>
            <div id="sliderImage" style="display:<?php echo $sliderUploadDisplay ?>;">            
                <?php echo $form->labelEx($model, 'sliderFile', array("style" => 'display:inline;')); ?>
                <?php echo $form->fileField($model, 'sliderFile', array("style" => 'display:inline;')); ?>
                <?php echo $form->error($model, 'sliderFile'); ?>
                <?php echo $drawSliderImage ?>
            </div>
        <?php endif; ?>

    </fieldset>

    <fieldset>
        <?php
        $drawImage = NULL;
        if ($model->article_id && $model->thumb) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['images']['path'] . "/" . $model->article_id . "." . $model->thumb))) {
                $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['images']['path'] . "/" . $model->article_id . "." . $model->thumb . "?" . time(), "", array("class" => "image", "width" => "100")) . '</div>';
            }
        }
        ?>
        <legend><?php echo AmcWm::t("msgsbase.core", "Image Options"); ?>:</legend>       
        <div class="row">
            <?php echo $form->labelEx($model, 'imageFile'); ?>
            <?php echo $form->fileField($model, 'imageFile'); ?>
            <?php echo $form->error($model, 'imageFile'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'image_description'); ?>
            <?php echo $form->textField($contentModel, 'image_description', array('size' => 100, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'image_description'); ?>
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

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Details"); ?>:</legend>             
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'article_pri_header'); ?>
            <?php echo $form->textField($contentModel, 'article_pri_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($contentModel, 'article_pri_header'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'article_header'); ?>
            <?php echo $form->textField($contentModel, 'article_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($contentModel, 'article_header'); ?>
        </div>
        <div class="row">
            <?php
            $this->widget('EditMulti', array(
                'id' => 'ArticlesTitles',
                'modelName' => 'ArticlesTitles',
                'data' => $contentModel->titles,
                'title' => AmcWm::t("msgsbase.core", "Add new title"),
                'elements' => array(
                    'title' => array(
                        'type' => 'text',
                        'maxlength' => 500,
                        'size' => 30,
                    ),
                ),
                'form' => $form,
                    )
            );
            ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model->news->getCurrent(), 'source'); ?>
            <?php echo $form->textField($model->news->getCurrent(), 'source', array('size' => 100, 'maxlength' => 100)); ?>
            <?php echo $form->error($model->news->getCurrent(), 'source'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'article_detail'); ?>
            <?php echo $form->error($contentModel, 'article_detail'); ?>
            <?php
            $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                'model' => $contentModel,
                'attribute' => 'article_detail',
                'editorTemplate' => 'full',
                'htmlOptions' => array(
                    'style' => 'height:300px; width:630px;'
                ),
                    )
            );
            ?>            
        </div>       
        <div class="row">                       
            <?php echo $form->labelEx($model, 'section_id'); ?>
            <?php echo $form->dropDownList($model, 'section_id', Sections::getSectionsList(), array('empty' => Yii::t('zii', 'Not set'))); ?>
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


        <div class="row">
            <?php echo $form->labelEx($model, 'writer_id'); ?>
            <?php echo $form->dropDownList($model, 'writer_id', Persons::getWritersList(Yii::t('zii', 'Not set'))); ?>
            <?php echo $form->error($model, 'writer_id'); ?>
        </div>

        <?php if ($options['default']['check']['addToInfocus']): ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'infocusId'); ?>
                <?php echo $form->dropDownList($model, 'infocusId', $this->getInfocus()); ?>
                <?php echo $form->error($model, 'infocusId'); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'publish_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'publish_date',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'hh:mm',
                    'changeMonth' => true,
                    'changeYear' => false,
                ),
                'htmlOptions' => array(
                    'class' => 'datebox',
                    'style' => 'direction:ltr',
                    'readonly' => 'readonly',
                    //'value' => ($model->publish_date) ? date("Y-m-d H:i", strtotime($model->publish_date)) : date("Y-m-d 00:01", strtotime("+1 day")),
                    'value' => ($model->publish_date) ? date("Y-m-d H:i", strtotime($model->publish_date)) : date("Y-m-d H:i"),
                )
            ));
            ?>
            <?php echo $form->error($model, 'publish_date'); ?>
        </div>

        <div class="row">            
            <?php echo $form->labelEx($model, 'expire_date'); ?>                        
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'expire_date',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'hh:mm',
                    'changeMonth' => true,
                    'changeYear' => false,
                ),
                'htmlOptions' => array(
                    'class' => 'datebox',
                    'style' => 'direction:ltr',
                    'readonly' => 'readonly',
                    'value' => ($model->expire_date) ? date("Y-m-d H:i", strtotime($model->expire_date)) : NULL,
                )
            ));
            ?>            
            <?php echo Chtml::checkBox('no_expiry', ($model->expire_date) ? 0 : 1, array('onclick' => '$("#Articles_expire_date").val("")')) ?>
            <?php echo Chtml::label(AmcWm::t($msgsBase, "No expiry date"), "remove_expiry", array("style" => 'display:inline;color:#3E4D57;font-weight:normal')) ?>
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

    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("amcBack", "Publish to the social media sites"); ?>:</legend>
            <?php //echo $form->labelEx($model, 'socialIds');      ?>
            <span>
                <?php echo $form->checkBoxList($model, 'socialIds', $this->getSocials(), array("separator" => "<br />", 'labelOptions' => array('class' => 'checkbox_label'))); ?>
            </span>
            <?php echo $form->error($model, 'socialIds'); ?>
        </fieldset>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->    
<?php
Yii::app()->clientScript->registerScript('displaySlider', "
    $('#Articles_in_slider').click(function(){
        if($('#Articles_in_slider').attr('checked')){
            $('#sliderImage').show();
        }
        else{
            $('#sliderImage').hide();
        }
    });
");
?>