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
        <?php echo $form->label($model, 'server_id'); ?>
        <?php echo $form->dropDownList($model, 'server_id', array(), array('prompt' => AmcWm::t("amcTools", 'All'))); ?>
        <?php echo $form->error($model, 'server_id'); ?>
    </div>
     <div class="row">
        <?php echo $form->label($model, 'zone_id'); ?>
        <?php echo $form->dropDownList($model, 'zone_id', array(), array('prompt' => AmcWm::t("amcTools", 'All'))); ?>
        <?php echo $form->error($model, 'zone_id'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- search-form -->