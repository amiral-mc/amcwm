<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
            ));
    ?>


    <div class="row">
        <?php echo $form->label($model, 'job'); ?>
        <?php echo $form->textField($model, 'job', array('size' => 60, 'maxlength' => 65)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'content_lang'); ?>
        <?php echo $form->dropDownList($model, 'content_lang', $this->getLanguages(), array('empty'=>'')); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->