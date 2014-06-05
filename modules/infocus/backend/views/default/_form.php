<div class="form">
    <?php
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    $model = $contentModel->getParentContent();
    $options = $this->module->appModule->options;
    $mediaSettings = $this->getModule()->appModule->mediaSettings;    
    $useBackground = isset($options['system']['check']['useBackground']) && $options['system']['check']['useBackground'];
    $useBanner = isset($options['system']['check']['useBanner']) && $options['system']['check']['useBanner'];
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

    <?php echo $form->errorSummary($model); ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Article Option"); ?>:</legend>
        <?php echo $form->checkBox($model, 'published'); ?>
        <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>            
        <?php echo $form->checkBox($model, 'archive'); ?>
        <?php echo $form->labelEx($model, 'archive', array("style" => 'display:inline;')); ?>               
    </fieldset>

    <fieldset>
        <?php
        $imageFile = null;
        if ($model->infocus_id && $model->thumb) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['list']['path'] . "/" . $model->infocus_id . "." . $model->thumb))) {
                $imageFile = Yii::app()->baseUrl . "/" . $mediaSettings['paths']['list']['path'] . "/" . $model->infocus_id . "." . $model->thumb . "?" . time();
            }
        }
        ?>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Image Options"); ?>:</legend>       
        <div class="row">
            <?php echo $form->labelEx($model, 'imageFile'); ?>
            <?php
            $this->widget('amcwm.widgets.imageUploader.ImageUploader', array(
                'model' => $model,
                'attribute' => 'imageFile',
                'thumbnailSrc' => $imageFile,
                'thumbnailInfo' => $mediaSettings['paths']['list']['info'],
                'sizesInfo' => $mediaSettings['paths'],
            ));
            ?>
            <?php echo $form->error($model, 'imageFile'); ?>
        </div>    

    </fieldset>
    <?php if($useBackground || $useBanner):?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Media Section"); ?>:</legend>
        <?php if($useBackground):?>
        <div class="row">
            <?php
            $drawBackground = NULL;
            if ($model->infocus_id && $model->background) {
                if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['backgrounds']['path'] . "/" . $model->infocus_id . "." . $model->background))) {
                    $drawBackground = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['backgrounds']['path'] . "/" . $model->infocus_id . "." . $model->background . "?" . time(), "", array("class" => "image", "width" => "100")) . '</div>';
                }
            }
            ?>
            <?php echo $form->labelEx($model, 'backgroundFile'); ?>
            <?php echo $form->fileField($model, 'backgroundFile'); ?>
            <?php echo $form->error($model, 'backgroundFile'); ?>
            <?php
            echo "<br />", AmcWm::t("msgsbase.core", "Image information", array(
                "{width}" => $mediaSettings['paths']['backgrounds']['info']['width']
                , "{height}" => $mediaSettings['paths']['backgrounds']['info']['height']
                , "{size}" => ($mediaSettings['paths']['backgrounds']["maxImageSize"] / 1024 / 1024)
            ));
            ?>.
            <div id="bgImg">        
                <?php echo $drawBackground; ?>
            </div>
            <?php if ($drawBackground): ?>
                <div class="row" style="clear: both; height: 20px;">
                    <input type="checkbox" name="deleteBgFile" id="deleteBgFile" style="float: right" onclick="deleteBgImage(this);" />
                    <label for="deleteBgFile" id="lbldltimg_2" title=""><span><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                    <label for="deleteBgFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='chklbl_2'><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('displayDeleteBgImage', "
                    deleteBgImage = function(chk){
                        if(chk.checked){
                            if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                                jQuery('#chklbl_2').text('" . CHtml::encode(AmcWm::t("amcBack", 'undo delete image')) . "');
                                jQuery('#bgImg').slideUp();
                                jQuery('#lbldltimg_2').toggleClass('isChecked');
                            }else{
                                chk.checked = false;
                            }
                        }else{
                            jQuery('#chklbl_2').text('" . CHtml::encode(AmcWm::t("amcBack", 'Delete Image')) . "');
                            jQuery('#bgImg').slideDown();
                            jQuery('#lbldltimg_2').toggleClass('isChecked');
                        }
                    }    
                ", CClientScript::POS_HEAD);

                Yii::app()->clientScript->registerCss('displayBGImageCss', "
                    label#lbldltimg_2 span {
                        display: none;
                    }
                    #deleteBgFile{
                        display: none;
                    }
                    label#lbldltimg_2 {
                        background:  url(" . $baseScript  . "/images/remove.png) no-repeat;
                        width: 18px;
                        height: 18px;
                        display: block;
                        cursor: pointer;
                        float:right;
                        margin: 3px;
                    }
                    label#lbldltimg_2.isChecked {
                        background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                    }
                ");

            endif;
            ?>
        </div>
        <?php endif;?>
        <?php if($useBanner):?>
        <div class="row">
            <?php
            $drawBanner = NULL;
            if ($model->infocus_id && $model->banner) {
                if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['banners']['path'] . "/" . $model->infocus_id . "." . $model->banner))) {
                    $drawBanner = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['banners']['path'] . "/" . $model->infocus_id . "." . $model->banner . "?" . time(), "", array("class" => "image", "width" => "100")) . '</div>';
                }
            }
            ?>
            <?php echo $form->labelEx($model, 'bannerFile'); ?>
            <?php echo $form->fileField($model, 'bannerFile'); ?>
            <?php echo $form->error($model, 'bannerFile'); ?>
            <?php
            echo "<br />", AmcWm::t("msgsbase.core", "Image information", array(
                "{width}" => $mediaSettings['paths']['banners']['info']['width']
                , "{height}" => $mediaSettings['paths']['banners']['info']['height']
                , "{size}" => ($mediaSettings['paths']['banners']["maxImageSize"] / 1024 / 1024)
            ));
            ?>.
            <div id="bnrImg">
                <?php echo $drawBanner; ?>
            </div>
            <?php if ($drawBanner): ?>
                <div class="row" style="clear: both; height: 20px;">
                    <input type="checkbox" name="deleteBnrFile" id="deleteBnrFile" style="float: right" onclick="deleteBnrImage(this);" />
                    <label for="deleteBnrFile" id="lbldltimg_3" title=""><span><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                    <label for="deleteBnrFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='chklbl_3'><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('displayDeleteBnrImage', "
                    deleteBnrImage = function(chk){
                        if(chk.checked){
                            if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                                jQuery('#chklbl_3').text('" . CHtml::encode(AmcWm::t("amcBack", 'undo delete image')) . "');
                                jQuery('#bnrImg').slideUp();
                                jQuery('#lbldltimg_3').toggleClass('isChecked');
                            }else{
                                chk.checked = false;
                            }
                        }else{
                            jQuery('#chklbl_3').text('" . CHtml::encode(AmcWm::t("amcBack", 'Delete Image')) . "');
                            jQuery('#bnrImg').slideDown();
                            jQuery('#lbldltimg_3').toggleClass('isChecked');
                        }
                    }    
                ", CClientScript::POS_HEAD);

                Yii::app()->clientScript->registerCss('displayBannerImageCss', "
                    label#lbldltimg_3 span {
                        display: none;
                    }
                    #deleteBnrFile{
                        display: none;
                    }
                    label#lbldltimg_3 {
                        background:  url(" . $baseScript  . "/images/remove.png) no-repeat;
                        width: 18px;
                        height: 18px;
                        display: block;
                        cursor: pointer;
                        float:right;
                        margin: 3px;
                    }
                    label#lbldltimg_3.isChecked {
                        background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                    }
                ");

            endif;
            ?>

        </div>
        <?php endif;?>
    </fieldset>
    <?php endif;?>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Article Details"); ?>:</legend>       
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'header'); ?>
            <?php echo $form->textField($contentModel, 'header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($contentModel, 'header'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'brief'); ?>
            <?php echo $form->error($contentModel, 'brief'); ?>
            <?php
            $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                'model' => $contentModel,
                'attribute' => 'brief',
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
            <?php echo $form->labelEx($contentModel, 'content_lang'); ?>
            <?php echo $form->dropDownList($contentModel, 'content_lang', $this->getLanguages()); ?>
            <?php echo $form->error($contentModel, 'content_lang'); ?>
        </div>        
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
            <?php echo Chtml::checkBox('no_expiry', ($model->expire_date) ? 0 : 1, array('onclick' => '$("#Infocus_expire_date").val("")')) ?>
            <?php echo Chtml::label(AmcWm::t("msgsbase.core", "No expiry date"), "remove_expiry", array("style" => 'display:inline;color:#3E4D57;font-weight:normal')) ?>
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
                'wordsCount' => Yii::app()->params["limits"]["wordsCount"], //  words in each box count
                'htmlOptions' => array(),
                    )
            );
            ?>            
        </div>     
    </fieldset>


    <?php $this->endWidget(); ?>

</div><!-- form -->    
<?php
Yii::app()->clientScript->registerScript('displaySlider', "
    $('#InfocusInFocus_in_slider').click(function(){
        if($('#InfocusInFocus_in_slider').attr('checked')){
            $('#sliderImage').show();
        }
        else{
            $('#sliderImage').hide();
        }
    });
");
?>