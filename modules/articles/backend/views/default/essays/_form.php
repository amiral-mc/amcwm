<div class="form">
    <?php
    $module = $this->module->appModule->currentVirtual;
    $options = $this->module->appModule->options;
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    $imagesInfo = $this->getModule()->appModule->mediaPaths;
    $model = $contentModel->getParentContent();
    $haveFlow = false;
    if (AmcWm::app()->hasComponent("workflow")) {
        if (AmcWm::app()->workflow->module->hasUserSteps()) {
            $currentFlow = AmcWm::app()->workflow->module->getFlowFromRoute($this->getRoute(), false);
            if (isset($currentFlow['step_title']['ManageContent'])) {
                $haveFlow = true;
            }
        }
    }
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
    <?php echo $form->errorSummary(array_merge(array($model, $contentModel), $contentModel->titles)); ?>
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
        if (!$haveFlow) {
            echo $form->checkBox($model, 'published');
            echo $form->labelEx($model, 'published', array("style" => 'display:inline;'));
        }
        ?>
        <?php echo $form->checkBox($model, 'archive'); ?>
        <?php echo $form->labelEx($model, 'archive', array("style" => 'display:inline;')); ?>
        <?php echo $form->checkBox($model, 'in_ticker'); ?>
        <?php echo $form->labelEx($model, 'in_ticker', array("style" => 'display:inline;')); ?>
        <?php echo $form->checkBox($model, 'in_list'); ?>
        <?php echo $form->labelEx($model, 'in_list', array("style" => 'display:inline;')); ?>
        <?php //echo $form->checkBox($model, 'in_spot'); ?>
        <?php //echo $form->labelEx($model, 'in_spot', array("style" => 'display:inline;')); ?>
        <?php if ($options['default']['check']['addToSlider']): ?>
            <div style="padding-top:5px;padding-bottom: 5px;">
                <?php echo $form->checkBox($model, 'in_slider', array('value' => ($model->in_slider) ? $model->in_slider : null)); ?>       
                <?php echo $form->labelEx($model, 'in_slider', array("style" => 'display:inline;')); ?>            
            </div>        
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


        <div class="row">


            <?php echo $form->labelEx($model, 'writer_id'); ?>
            <?php
            $writerSelected = array();
            if ($model->writer_id) {
                $writer = "[{$model->writer->person->getCurrent()->name}]";
                if ($model->writer->person->email) {
                    $writer .= " [{$model->writer->person->email}]";
                }
                $writerSelected = array('id' => $model->writer_id, 'text' => $writer);
            }
            $this->widget('amcwm.core.widgets.select2.ESelect2', array(
                'model' => $model,
                'attribute' => "writer_id",
                'initSelection' => $writerSelected,
                'options' => array(
                    "dropdownCssClass" => "bigdrop",
                    "placeholder" => AmcWm::t('amcTools', 'Enter Search Keywords'),
                    'ajax' => array(
                        'dataType' => "json",
                        "quietMillis" => 100,
                        'url' => Html::createUrl('/backend/articles/default/ajax', array('do' => 'findWriters')),
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
        <legend><?php echo AmcWm::t($msgsBase, "Parent Article"); ?>:</legend>
        <?php echo Chtml::checkBox('relatedToSection', 1, array('onclick' => '', 'id' => 'relatedToSection')) ?>
        <?php echo Chtml::label(AmcWm::t($msgsBase, "List articles related to the selected section"), "relatedToSection", array("style" => 'display:inline;color:#3E4D57;font-weight:normal')) ?>
        <br />
        <?php
        $select2Defaults = array();
        if ($model->parent_article) {
            $defaultValue = $model->parent_article;
            $text = $model->parentArticle->getCurrent()->article_header;
            $select2Defaults = array('id' => $defaultValue, 'text' => $text);
        }

        echo AmcWm::t($msgsBase, "If this article belongs to another article, you can select it by clicking here");
        echo "<br />";
        $this->widget('amcwm.core.widgets.select2.ESelect2', array(
            'model' => $model,
            'attribute' => "parent_article",
            'initSelection' => $select2Defaults,
            'options' => array(
                "dropdownCssClass" => "bigdrop",
                "placeholder" => AmcWm::t('amcTools', 'Enter Search Keywords'),
                'minimumInputLength' => '3',
                'ajax' => array(
                    'dataType' => "json",
                    "quietMillis" => 100,
                    'url' => Html::createUrl('/backend/articles/default/ajax', array('do' => 'findArticle', 'artId' => $model->article_id)),
                    'data' => 'js:function (term, page) { 
                        var sectionId = $("#' . CHtml::getIdByName(CHtml::activeName($model, 'section_id')) . ' :selected").val();
                        // page is the one-based page number tracked by Select2                        
                        var dataQuery = {q: term, page: page}    
                        if($("#relatedToSection").is(":checked") && sectionId){
                            dataQuery.sId = sectionId;
                        }                                                
                        return dataQuery;
                    }',
                    'results' => 'js:function (data, page) {
                         var more = (page * ' . Articles::REF_PAGE_SIZE . ') < data.total; // whether or not there are more results available 
                         // notice we return the value of more so Select2 knows if more results can be loaded
                         return {results: data.records, more: more};
                     }',
                ),
            ),
            'htmlOptions' => array(
                'style' => 'min-width:500px;',
            ),
        ));
        ?>
        <div>
            <?php echo CHtml::checkBox("remove_parent") . " " . AmcWm::t($msgsBase, "Remove Parent Article"); ?>
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

    <?php if (isset($options['default']['check']['allowPageImage']) && $options['default']['check']['allowPageImage']): ?>
        <fieldset>
            <?php
            $drawPageImage = NULL;
            if (isset($model->page_img) && $model->article_id && $model->page_img) {
                if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['pageImage']['path'] . "/" . $model->article_id . "." . $model->page_img))) {
                    $drawPageImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['pageImage']['path'] . "/" . $model->article_id . "." . $model->page_img . "?" . time(), "", array("class" => "image", "width" => "200")) . '</div>';
                }
            }
            ?>
            <legend><?php echo AmcWm::t("msgsbase.core", "Page Image Options"); ?>:</legend>       
            <div class="row">
                <?php echo $form->labelEx($model, 'pageImg'); ?>
                <?php echo $form->fileField($model, 'pageImg'); ?>
                <?php echo $form->error($model, 'pageImg'); ?>
            </div>

            <div id="itemPageImageFile">
                <?php echo $drawPageImage ?>
            </div>

            <?php if ($drawPageImage): ?>
                <div class="row">
                    <input type="checkbox" name="deletePageImage" id="deletePageImage" style="float: right" onclick="deleteRelatedPageImage(this);" />
                    <label for="deletePageImage" id="lbldltPageimg" title=""><span><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                    <label for="deletePageImage" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='chklblPage'><?php echo AmcWm::t("amcBack", 'Delete Image'); ?></span></label>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('displayDeletePageImage', "
                deleteRelatedPageImage = function(chk){
                    if(chk.checked){
                        if(confirm('" . CHtml::encode(AmcWm::t("amcBack", 'Are you sure you want to delete this image?')) . "')){
                            jQuery('#chklblPage').text('" . CHtml::encode(AmcWm::t("amcBack", 'undo delete image')) . "');
                            jQuery('#itemPageImageFile').slideUp();
                            jQuery('#lbldltPageimg').toggleClass('isChecked');
                        }else{
                            chk.checked = false;
                        }
                    }else{
                        jQuery('#chklblPage').text('" . CHtml::encode(AmcWm::t("amcBack", 'Delete Image')) . "');
                        jQuery('#itemPageImageFile').slideDown();
                        jQuery('#lbldltPageimg').toggleClass('isChecked');
                    }
                }    
            ", CClientScript::POS_HEAD);

                Yii::app()->clientScript->registerCss('displayPageImageCss', "
                label#lbldltPageimg span {
                    display: none;
                }
                #deletePageImage{
                    display: none;
                }
                label#lbldltPageimg {
                    background:  url(" . $baseScript . "/images/remove.png) no-repeat;
                    width: 18px;
                    height: 18px;
                    display: block;
                    cursor: pointer;
                    float:right;
                    margin: 3px;
                }
                label#lbldltPageimg.isChecked {
                    background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                }
            ");
            endif;
            ?>
        </fieldset>
    <?php endif; // end if drow page image  ?>

    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("amcBack", "Publish to the social media sites"); ?>:</legend>
            <?php //echo $form->labelEx($model, 'socialIds');       ?>
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
