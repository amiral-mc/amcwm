<div class="wide form">

    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
            ));
    ?>

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
        <?php echo $form->label($contentModel, 'title'); ?>
        <?php echo $form->textField($contentModel, 'title', array('size' => 60, 'maxlength' => 65)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($contentModel, 'content_lang'); ?>
        <?php echo $form->dropDownList($contentModel, 'content_lang', $this->getLanguages(), array('empty' => '')); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->