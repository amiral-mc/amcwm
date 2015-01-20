<div class="form">
    <?php
    /**
     * @todo fix the client side validation in the tinyMCE editor.
     */
    $form = $this->beginWidget('CActiveForm', array(
                'id' => $formId,
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                )                
            ));
    ?>

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary(array($model)); ?>
    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("amcwm.core.backend.messages.comments", "General Options"); ?>:</legend>
            <?php echo $form->checkBox($model, 'published'); ?>
            <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
            
            <?php echo $form->checkBox($model, 'hide'); ?>
            <?php echo $form->labelEx($model, 'hide', array("style" => 'display:inline;')); ?>
            
            <?php echo $form->checkBox($model, 'force_display'); ?>
            <?php echo $form->labelEx($model, 'force_display', array("style" => 'display:inline;')); ?>
            
        </fieldset> 
    </div>
    <div class="row">
        <fieldset>
            <legend><?php echo AmcWm::t("amcwm.core.backend.messages.comments", "Comment Details"); ?>:</legend>       
            <div class="row">
                <?php echo $form->labelEx($model, 'comment_header'); ?>
                <?php echo $form->textField($model, 'comment_header', array('size' => 60, 'maxlength' => 100)); ?>
                <?php echo $form->error($model, 'comment_header'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'comment'); ?>
                <?php echo $form->error($model, 'comment'); ?>
                <?php
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                    'model' => $model,
                    'attribute' => 'comment',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:600px;'
                    ),
                        )
                );
                ?>
            </div>
        </fieldset>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->