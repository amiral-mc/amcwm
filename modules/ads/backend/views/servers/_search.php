<div class="wide form">
    <p>
        <?php echo AmcWm::t("amcBack", "You may optionally enter a comparison operator (&lt;, &lt;= &gt; ,&gt;=, &lt;&gt; or =) at the beginning of each of your search values to specify how the comparison should be done."); ?>
    </p>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'server_name'); ?>
        <?php echo $form->textField($model, 'server_name', array('size' => 60, 'maxlength' => 100)); ?>
        <?php echo $form->error($model, 'server_name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'header_code'); ?>
        <?php echo $form->textArea($model, 'header_code', array('dir' => 'ltr')); ?>
        <?php echo $form->error($model, 'header_code'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- search-form -->