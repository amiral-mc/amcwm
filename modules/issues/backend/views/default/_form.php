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
                ),
                'htmlOptions' => array('enctype' => 'multipart/form-data')
            ));
    echo CHtml::hiddenField("issue", Issue::getInstance()->getIssueValue());
    ?>

    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary($model); ?>


    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Create New Issue"); ?>:</legend>             
        <!--  Add Parent sections here       -->
        <div class="row">
            <?php echo $form->labelEx($model, 'issue_id', array("style"=>"display:inline;")); ?>:&nbsp;<b><?php echo $model->issue_id + 1; ?></b>
        </div>
        <div class="row">
            <?php echo CHtml::label( AmcWm::t("msgsbase.core", "Issue Date"), "" ,array("style"=>"display:inline;")); ?>:&nbsp;<b><?php echo $model->getNewIssueDate(); ?></b>
        </div>
    </fieldset>
    

    <?php $this->endWidget(); ?>

</div><!-- form -->    