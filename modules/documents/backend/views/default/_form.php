<div class="form">
    <?php
    $mediaSettings = $this->module->appModule->mediaSettings;
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
//        'enableClientValidation' => true,
//        'clientOptions' => array(
//            'validateOnSubmit' => true,
//        ),
        'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Document data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>

        <div class="row">
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'category_id'); ?>
            <?php echo $form->dropDownList($model, 'category_id', DocsCategories::getCategoriesList(), array("prompt"=>"--")); ?>
            <?php echo $form->error($model, 'category_id'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'file_lang'); ?>
            <?php echo $form->dropDownList($model, 'file_lang', AmcWm::app()->appModule->getSettings('languages'), array('prompt' => '---')); ?>
            <?php echo $form->error($model, 'file_lang'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'title'); ?>
            <?php echo $form->textField($contentModel, 'title', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($contentModel, 'title'); ?>
        </div>

        <div>

            <legend><?php echo AmcWm::t("msgsbase.core", "Doc File"); ?>:</legend>

            <?php if ($model->file_ext): ?>
                <div class="row">
                    <input type="checkbox" name="deleteFile" id="deleteFile" style="float: right" onclick="deleteRelatedFile(this);" />
                    <label for="deleteFile" id="labelDelFile" title=""><span><?php echo AmcWm::t("msgsbase.core", 'Delete Document'); ?></span></label>
                    <label for="deleteFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='checlFileLabel'><?php echo AmcWm::t("msgsbase.core", 'Delete Document'); ?></span></label>
                    <label for="downlaodFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='checlFileLabel'> &nbsp;-&nbsp; <a href="<?php echo Html::createUrl('/site/download', array('f' => "{$mediaSettings['paths']['files']['path']}/{$model->doc_id}.{$model->file_ext}")) ?>"><?php echo AmcWm::t("msgsbase.core", 'Download the file'); ?></a></span></label>
                </div>
                <?php
                Yii::app()->clientScript->registerScript('displayDeleteFile', "
                    deleteRelatedFile = function(chk){
                        if(chk.checked){
                            if(confirm('" . CHtml::encode(AmcWm::t("msgsbase.core", 'Are you sure you want to delete this document?')) . "')){
                                jQuery('#checlFileLabel').text('" . CHtml::encode(AmcWm::t("msgsbase.core", 'undo delete document')) . "');
                                jQuery('#labelDelFile').toggleClass('isChecked');
                            }else{
                                chk.checked = false;
                            }
                        }else{
                            jQuery('#checlFileLabel').text('" . CHtml::encode(AmcWm::t("msgsbase.core", 'Delete Document')) . "');
                            jQuery('#labelDelFile').toggleClass('isChecked');
                        }
                    }    
                ", CClientScript::POS_HEAD);

                Yii::app()->clientScript->registerCss('displayFileCss', "
                    label#labelDelFile span {
                        display: none;
                    }
                    #deleteFile{
                        display: none;
                    }
                    label#labelDelFile {
                        background:  url(" . $baseScript . "/images/remove.png) no-repeat;
                        width: 18px;
                        height: 18px;
                        display: block;
                        cursor: pointer;
                        float:right;
                        margin: 3px;
                    }
                    label#labelDelFile.isChecked {
                        background:  url(" . $baseScript . "/images/undo.png) no-repeat;
                    }
                ");
            endif;
            ?><br />
            <div class="row">
                <?php // echo $form->labelEx($model, 'docFile');  ?>
                <?php echo $form->fileField($model, 'docFile'); ?>
                <?php echo $form->error($model, 'docFile'); ?>
                <br /><br />
                <?php
                $mediaSettings = AmcWm::app()->appModule->mediaSettings;
                echo AmcWm::t('msgsbase.core', 'Files allowed "{files}"', array('{files}' => $mediaSettings['info']['extensions']))
                ?>
            </div>
        </div>

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

        <div class="row">
            <?php echo $form->labelEx($model, 'start_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'start_date',
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
                    'value' => ($model->start_date) ? date("Y-m-d H:i", strtotime($model->start_date)) : date("Y-m-d H:i"),
                )
            ));
            ?>
            <?php echo $form->error($model, 'start_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'end_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'end_date',
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
                    'value' => ($model->end_date) ? date("Y-m-d H:i", strtotime($model->end_date)) : '',
                )
            ));
            ?>
            <?php echo $form->error($model, 'end_date'); ?>
        </div>



    </fieldset>  
    <?php $this->endWidget(); ?>

</div><!-- form -->