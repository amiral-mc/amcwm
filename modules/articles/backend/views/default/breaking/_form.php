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
    <?php echo $form->errorSummary(array_merge(array($model, $contentModel, $model->news), $contentModel->titles)); ?>
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
    </fieldset>


    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Details"); ?>:</legend>             
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'article_header'); ?>
            <?php echo $form->textField($contentModel, 'article_header', array('size' => 60, 'maxlength' => 500)); ?>
            <?php echo $form->error($contentModel, 'article_header'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model->news, 'source_id'); ?>
            <?php
                $initSourceSelection = ($model->news->source) ? array('id' => $model->news->source_id, 'text' => $model->news->source->getCurrent()->source) : array();
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
            <?php echo $form->labelEx($model, 'section_id'); ?>            
            <?php
            $this->widget('amcwm.core.widgets.select2.ESelect2', array(
                    'model' => $model,
                    'attribute' => "section_id",
                    'useSelect'=>true,
                    'data'=>Sections::getSectionsList(),
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
            <?php echo Chtml::label(AmcWm::t("msgsbase.core", "No expiry date"), "remove_expiry", array("style" => 'display:inline;color:#3E4D57;font-weight:normal')) ?>
            <?php echo $form->error($model, 'expire_date'); ?>
        </div>
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
    </fieldset>   
    <?php $this->endWidget(); ?>
</div><!-- form -->    