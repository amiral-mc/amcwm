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
        <?php echo $form->labelEx($model, 'company_id'); ?>
        <?php echo $form->dropDownList($model, 'company_id', CHtml::listData(ExchangeCompanies::model()->findAll(array('order' => 'company_name ASC')), 'company_id', 'company_name')); ?>
        <?php echo $form->error($model, 'company_id'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'index'); ?>
        <?php echo $form->textField($model, 'index'); ?>
        <?php echo $form->error($model, 'index'); ?>
    </div>
    <div class="row">                       
        <?php echo $form->labelEx($model, 'percentage'); ?>
        <?php echo $form->textField($model, 'percentage'); ?>
        <?php echo $form->error($model, 'percentage'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'net'); ?>
        <?php echo $form->textField($model, 'net'); ?>
        <?php echo $form->error($model, 'net'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- search-form -->