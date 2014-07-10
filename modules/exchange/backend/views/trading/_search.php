<?php
/**
 * @todo bug in CJuiDatePicker cannot see date window when open picker from date field then goto another date field before select the date
 */
?>
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
        <?php echo $form->labelEx($model, 'exchange_id'); ?>
        <?php echo $form->dropDownList($model, 'exchange_id', CHtml::listData(Exchange::model()->findAll(array('order' => 'exchange_name DESC')), 'exchange_id', 'exchange_name')); ?>
        <?php echo $form->error($model, 'exchange_id'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'exchange_date'); ?>
        <?php echo $form->dateField($model, 'exchange_date'); ?>
        <?php echo $form->error($model, 'exchange_date'); ?>
    </div>
    <div class="row">                       
        <?php echo $form->labelEx($model, 'trading_value'); ?>
        <?php echo $form->textField($model, 'trading_value'); ?>
        <?php echo $form->error($model, 'trading_value'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'shares_of_stock'); ?>
        <?php echo $form->textField($model, 'shares_of_stock'); ?>
        <?php echo $form->error($model, 'shares_of_stock'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'closing_value'); ?>
        <?php echo $form->textField($model, 'closing_value'); ?>
        <?php echo $form->error($model, 'closing_value'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'difference_value'); ?>
        <?php echo $form->textField($model, 'difference_value'); ?>
        <?php echo $form->error($model, 'difference_value'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'difference_percentage'); ?>
        <?php echo $form->textField($model, 'difference_percentage'); ?>
        <?php echo $form->error($model, 'difference_percentage'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- search-form -->