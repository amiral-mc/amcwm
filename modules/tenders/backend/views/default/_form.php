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
        <legend><?php echo AmcWm::t("msgsbase.core", "General data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>


        <div class="row">
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'department_id'); ?>
            <?php echo $form->dropDownList($model, 'department_id', TendersDepartment::getDepartmentsList(), array("prompt" => "--")); ?>
            <?php echo $form->error($model, 'department_id'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'tender_type'); ?>
            <?php echo $form->dropDownList($model, 'tender_type', Tenders::model()->getTenderTypes(), array("prompt" => "--")); ?>
            <?php echo $form->error($model, 'tender_type'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'tender_status'); ?>
            <?php echo $form->dropDownList($model, 'tender_status', Tenders::model()->getTenderStatus(), array("prompt" => "--")); ?>
            <?php echo $form->error($model, 'tender_status'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'activities'); ?>
            <?php echo $form->dropDownList($model, 'activities', TendersActivities::model()->getActivitiesList(), array('multiple' => 'multiple')); ?>
            <?php echo $form->error($model, 'activities'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'title'); ?>
            <?php echo $form->textField($contentModel, 'title', array('size' => 150, 'maxlength' => 255)); ?>
            <?php echo $form->error($contentModel, 'title'); ?>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "More Details"); ?>:</legend>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'description'); ?>
            <?php echo $form->error($contentModel, 'description'); ?>
            <?php
            $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                'model' => $contentModel,
                'attribute' => 'description',
                'editorTemplate' => 'full',
                'htmlOptions' => array(
                    'style' => 'height:300px; width:430px;'
                ),
                    )
            );
            ?>   
        </div>

        <div class="row">
            <?php echo $form->labelEx($contentModel, 'conditions'); ?>
            <?php echo $form->error($contentModel, 'conditions'); ?>
            <?php
            $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                'model' => $contentModel,
                'attribute' => 'conditions',
                'editorTemplate' => 'full',
                'htmlOptions' => array(
                    'style' => 'height:300px; width:430px;'
                ),
                    )
            );
            ?>   
        </div>
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'notes'); ?>
            <?php echo $form->error($contentModel, 'notes'); ?>
            <?php
            $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                'model' => $contentModel,
                'attribute' => 'notes',
                'editorTemplate' => 'full',
                'htmlOptions' => array(
                    'style' => 'height:300px; width:430px;'
                ),
                    )
            );
            ?>   
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'rfp_start_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'rfp_start_date',
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
                    'value' => ($model->rfp_start_date) ? date("Y-m-d H:i", strtotime($model->rfp_start_date)) : date("Y-m-d H:i"),
                )
            ));
            ?>
            <?php echo $form->error($model, 'rfp_start_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'rfp_end_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'rfp_end_date',
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
                    'value' => ($model->rfp_end_date) ? date("Y-m-d H:i", strtotime($model->rfp_end_date)) : date("Y-m-d H:i", strtotime("+15 day")),
                )
            ));
            ?>
            <?php echo $form->error($model, 'rfp_end_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'submission_start_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'submission_start_date',
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
                    'value' => ($model->submission_start_date) ? date("Y-m-d H:i", strtotime($model->submission_start_date)) : null,
                )
            ));
            ?>
            <?php echo $form->error($model, 'submission_start_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'submission_end_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'submission_end_date',
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
                    'value' => ($model->submission_end_date) ? date("Y-m-d H:i", strtotime($model->submission_end_date)) : null,
                )
            ));
            ?>
            <?php echo $form->error($model, 'submission_end_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'technical_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'technical_date',
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
                    'value' => ($model->technical_date) ? date("Y-m-d H:i", strtotime($model->technical_date)) : null,
                )
            ));
            ?>
            <?php echo $form->error($model, 'technical_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'financial_date'); ?>
            <?php
            $this->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', array(
                'model' => $model,
                'attribute' => 'financial_date',
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
                    'value' => ($model->financial_date) ? date("Y-m-d H:i", strtotime($model->financial_date)) : null,
                )
            ));
            ?>
            <?php echo $form->error($model, 'financial_date'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'rfp_price1'); ?>
            <?php echo $form->textField($model, 'rfp_price1', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'rfp_price1'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'rfp_price2'); ?>
            <?php echo $form->textField($model, 'rfp_price2', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'rfp_price2'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'primary_insurance'); ?>
            <?php echo $form->textField($model, 'primary_insurance', array('size' => 10, 'maxlength' => 10)); ?>
            <?php echo $form->error($model, 'primary_insurance'); ?>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "File"); ?>:</legend>

        <?php if ($model->file_ext): ?>
            <div class="row">
                <input type="checkbox" name="deleteFile" id="deleteFile" style="float: right" onclick="deleteRelatedFile(this);" />
                <label for="deleteFile" id="labelDelFile" title=""><span><?php echo AmcWm::t("msgsbase.core", 'Delete Tender'); ?></span></label>
                <label for="deleteFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='checlFileLabel'><?php echo AmcWm::t("msgsbase.core", 'Delete Tender'); ?></span></label>
                <label for="downlaodFile" title="" style='float: right;margin-top: 4px;cursor: pointer'><span id='checlFileLabel'> &nbsp;-&nbsp; <a href="<?php echo $this->createUrl('/site/download', array('f' => "{$mediaSettings['paths']['files']['path']}/{$model->tender_id}.{$model->file_ext}")) ?>"><?php echo AmcWm::t("msgsbase.core", 'Download the file'); ?></a></span></label>
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
                            jQuery('#checlFileLabel').text('" . CHtml::encode(AmcWm::t("msgsbase.core", 'Delete Tender')) . "');
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
        ?>
        <div class="row" style="clear: both; ">
            <?php // echo $form->labelEx($model, 'docFile');  ?>
            <?php echo $form->fileField($model, 'docFile'); ?>
            <?php echo $form->error($model, 'docFile'); ?>
            <div style="padding: 5px;"><?php echo AmcWm::t('msgsbase.core', 'Files allowed "{files}"', array('{files}' => $mediaSettings['info']['extensions'])); ?></div>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div><!-- form -->