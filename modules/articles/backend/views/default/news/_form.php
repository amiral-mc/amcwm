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

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage()); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam()); ?>
    <?php
    $models[] = $model;
    $models[] = $contentModel;
    $models[] = $model->news;
    foreach ($contentModel->attachment as $attachmentModel) {
        $models[] = $attachmentModel;
    }
    foreach ($contentModel->titles as $title) {
        $models[] = $title;
    }
    echo $form->errorSummary($models);
    ?>
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
                <span>-</span>
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.news", 'Breaking News'); ?></span>:
                <span class="translated_org_item">
                    <?php
                    if ($model->news->is_breaking) {
                        echo AmcWm::t("amcFront", "Yes");
                    } else {
                        echo AmcWm::t("amcFront", "No");
                    }
                    ?>
                </span>
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
                <input id="Articles_sliderFile_watermark" name="Articles[sliderFile_watermark]" type="checkbox" /> <?php echo AmcWm::t("amcBack", 'Use watermark'); ?>
                <?php echo $form->error($model, 'sliderFile'); ?>
                <?php echo $drawSliderImage ?>
            </div>
        <?php endif; ?>

    </fieldset>

    <fieldset>
        <?php
        $imageFile = null;
        if ($model->article_id && $model->thumb) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['list']['path'] . "/" . $model->article_id . "." . $model->thumb))) {
                $imageFile = Yii::app()->baseUrl . "/" . $imagesInfo['list']['path'] . "/" . $model->article_id . "." . $model->thumb . "?" . time();
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
                'thumbnailInfo' => $imagesInfo['list']['info'],
                'sizesInfo' => $imagesInfo,
            ));
            ?>
            <?php echo $form->error($model, 'imageFile'); ?>
        </div>    

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'image_description'); ?>
            <?php echo $form->textField($contentModel, 'image_description', array('size' => 100, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'image_description'); ?>
        </div>

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
            <?php echo $form->labelEx($model->news, 'source_id'); ?>
            <?php
            $initSourceSelection = array();
            if(($model->news->source_id)){
                $currentSource = $model->news->source->getCurrent();
                if($currentSource){
                    $initSourceSelection = array('id' => $model->news->source_id, 'text' => $currentSource->source);
                }
                else{
                    $initSourceSelection = array('id' => $model->news->source_id, 'text' => $model->news->source_id);
                }
            }            
            $this->widget('amcwm.core.widgets.select2.ESelect2', array(
                'model' => $model->news,
                'attribute' => "source_id",
                'initSelection' => $initSourceSelection,
                'options' => array(
                    "dropdownCssClass" => "bigdrop",
                    "placeholder" => AmcWm::t('amcTools', 'Enter Search Keywords'),
                    'ajax' => array(
                        'dataType' => "json",
                        "quietMillis" => 100,
                        'url' => Html::createUrl('/backend/articles/default/ajax', array('do' => 'findSources')),
                        'data' => 'js:function (term, page) { // page is the one-based page number tracked by Select2
                        return {
                               q: term, //search term
                               page: page, // page number                     
                           };
                       }',
                        'results' => 'js:function (data, page) {
                            var more = (page * ' . NewsSources::REF_PAGE_SIZE . ') < data.total; // whether or not there are more results available 
                            // notice we return the value of more so Select2 knows if more results can be loaded
                            return {results: data.records, more: more};
                          }',
                    ),
                ),
                'htmlOptions' => array(
                    'style' => 'min-width:400px;',
                ),
            ));
            ?>

            <?php echo $form->error($model->news, 'source_id'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'article_detail'); ?>
            <?php echo $form->error($contentModel, 'article_detail'); ?>
            <?php echo $form->richTextField($contentModel, 'article_detail', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>           
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

        <?php if (count($model->news->hasEditors)): ?>
            <div class="row">


                <?php echo $form->labelEx($model->news, 'editorsIds'); ?>            
                <?php
                $editorSelected = array();
                foreach ($model->news->editors as $newsEditor) {
                    $editor = "[{$newsEditor->editor->person->getCurrent()->name}]";
                    if ($newsEditor->editor->person->email) {
                        $editor .= " [{$newsEditor->editor->person->email}]";
                    }
                    $editorSelected[] = array('id' => $newsEditor->editor_id, 'text' => $editor);
                }
                $this->widget('amcwm.core.widgets.select2.ESelect2', array(
                    'model' => $model->news,
                    'attribute' => "editorsIds",
                    'initSelection' => $editorSelected,
                    'options' => array(
                        'multiple' => true,
                        "dropdownCssClass" => "bigdrop",
                        "placeholder" => AmcWm::t('amcTools', 'Enter Search Keywords'),
                        'ajax' => array(
                            'dataType' => "json",
                            "quietMillis" => 100,
                            'url' => Html::createUrl('/backend/articles/default/ajax', array('do' => 'findEditors')),
                            'data' => 'js:function (term, page) { // page is the one-based page number tracked by Select2
                        return {
                               q: term, //search term
                               page: page, // page number                     
                           };
                       }',
                            'results' => 'js:function (data, page) {
                            var more = (page * ' . Writers::REF_PAGE_SIZE . ') < data.total; // whether or not there are more results available 
                            // notice we return the value of more so Select2 knows if more results can be loaded
                            return {results: data.records, more: more};
                          }',
                        ),
                    ),
                    'htmlOptions' => array(
                        'style' => 'min-width:400px;',
                    ),
                ));
                ?>
                <?php echo $form->error($model->news, 'editorsIds'); ?>
            </div>
        <?php endif; ?>

        <?php if ($options['default']['check']['addToInfocus']): ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'infocusId'); ?>
                <?php echo $form->dropDownList($model, 'infocusId', $this->getInfocus()); ?>
                <?php echo $form->error($model, 'infocusId'); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'publish_date'); ?>
            <?php echo $form->calendarField($model, 'publish_date', array('class' => 'datebox', 'dateOptions' => array("dateOnly" => 0))); ?>           
            <?php echo $form->error($model, 'publish_date'); ?>
        </div>

        <div class="row">                        
            <?php echo $form->labelEx($model, 'expire_date'); ?>                        
            <?php echo $form->calendarField($model, 'expire_date', array('class' => 'datebox', 'dateOptions' => array("dateOnly" => 0), 'value' => ($model->expire_date) ? date("Y-m-d H:i", strtotime($model->expire_date)) : '',)); ?>
            <?php echo Chtml::checkBox('no_expiry', ($model->expire_date) ? 0 : 1, array('onclick' => '$("#Articles_expire_date").val("")')) ?>
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
                'wordsCount' => Yii::app()->params["limits"]["wordsCount"], // words in each box count
                'htmlOptions' => array(),
                    )
            );
            ?>            
        </div>     
    </fieldset>
    <?php if (!$model->news->is_breaking): ?>
        <div class="row">
            <fieldset>
                <legend><?php echo AmcWm::t("amcBack", "Publish to the social media sites"); ?>:</legend>
                <?php //echo $form->labelEx($model, 'socialIds');         ?>
                <span>
                    <?php echo $form->checkBoxList($model, 'socialIds', $this->getSocials(), array("separator" => "<br />", 'labelOptions' => array('class' => 'checkbox_label'))); ?>
                </span>
                <?php echo $form->error($model, 'socialIds'); ?>
            </fieldset>
        </div>
    <?php endif; ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Files"); ?>:</legend>
        <div class="row">
            <?php
            echo $form->attachmentField($contentModel, 'attachment', array("id" => "attachment_area"));
            ?>
        </div>     
    </fieldset>

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