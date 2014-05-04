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
        <?php echo $form->label($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 65)); ?>
    </div>
    
    <div class="row">
        <?php echo $form->label($model, 'marital'); ?>
        <?php echo $form->textField($model, 'marital', array('size' => 60, 'maxlength' => 65)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack",'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->