<?php
/* @var $this ExchangeController */
/* @var $model Exchange */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

    <div class="row">
        <?php echo $form->label($model,'exchange_id'); ?>
        <?php echo $form->textField($model,'exchange_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'exchange_name'); ?>
        <?php echo $form->textField($model,'exchange_name',array('size'=>45,'maxlength'=>45)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'currency'); ?>
        <?php echo $form->textField($model,'currency',array('size'=>45,'maxlength'=>45)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->