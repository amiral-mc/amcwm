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
        <?php echo $form->labelEx($model, 'published', array("style" => 'display:inline;')); ?>
        <?php echo $form->checkBox($model, 'published'); ?>
        <?php echo $form->error($model, 'published'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'exchange_id'); ?>
        <?php echo $form->dropDownList($model, 'exchange_id', CHtml::listData(Exchange::model()->findAll(array('order' => 'exchange_name DESC')), 'exchange_id', 'exchange_name')); ?>
        <?php echo $form->error($model, 'exchange_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'company_name'); ?>
        <?php echo $form->textField($model, 'company_name', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'company_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'code'); ?>
        <?php echo $form->textField($model, 'code', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'code'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- search-form -->