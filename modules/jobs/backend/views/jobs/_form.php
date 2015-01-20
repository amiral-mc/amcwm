<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('Form', array(
                'id' => $formId,
                'enableAjaxValidation' => false,
//                'enableClientValidation' => true,
//                'clientOptions' => array(
//                    'validateOnSubmit' => true,
//                ),
            ));
    ?>
    <fieldset>        
        <legend><?php echo AmcWm::t("msgsbase.core", "Item data"); ?>:</legend>
        <p class="note"><?php echo AmcWm::t("amcFront", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
        <?php echo $form->errorSummary(array($model, $contentModel)); ?>
        
        <div class="row">                       
            <?php echo $form->labelEx($model, 'category_id'); ?>
            <?php echo $form->dropDownList($model, 'category_id', Jobs::getCategoriesList(), array('empty' => Yii::t('zii', 'Not set'))); ?>
            <?php echo $form->error($model, 'category_id'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'job'); ?>
            <?php echo $form->textField($contentModel, 'job', array('size' => 60, 'maxlength' => 65)); ?>
            <?php echo $form->error($contentModel, 'job'); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($contentModel, 'job_description'); ?>
            <?php 
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                        'model' => $contentModel,
                        'attribute' => 'job_description',
                        'editorTemplate' => 'full',
                        'htmlOptions' => array(
                            'style' => 'height:300px; width:630px;'
                        ),
                    )
                );
            ?>
            <?php echo $form->error($contentModel, 'job_description'); ?>
        </div>
        <div>
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'publish_date'); ?>           
            <?php echo $form->calendarField($model, 'publish_date', array('class' => 'datebox', 'dateOptions' => array("dateOnly" => 0))); ?>           
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
            <?php echo Chtml::checkBox('no_expiry', ($model->expire_date) ? 0 : 1, array('onclick' => '$("#Jobs_expire_date").val("")')) ?>
            <?php echo Chtml::label(AmcWm::t("msgsbase.core", "No expiry date"), "remove_expiry", array("style" => 'display:inline;color:#3E4D57;font-weight:normal')) ?>
            <?php echo $form->error($model, 'expire_date'); ?>
        </div>
        
    </fieldset>        
    <?php $this->endWidget(); ?>

</div><!-- form -->