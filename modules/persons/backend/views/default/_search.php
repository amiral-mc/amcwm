<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
            ));
    ?>


    <div class="row">
        <?php echo $form->label($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 65)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model->parentContent, 'email'); ?>
        <?php echo $form->textField($model->parentContent, 'email', array('size' => 60, 'maxlength' => 65)); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcFront", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->