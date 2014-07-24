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
        <?php echo $form->labelEx($model->parentContent(), 'published', array("style" => 'display:inline;')); ?>
        <?php echo $form->checkBox($model->parentContent(), 'published'); ?>
        <?php echo $form->error($model->parentContent(), 'published'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->parentContent(), 'exchange_id'); ?>
        <?php echo $form->dropDownList($model->parentContent(), 'exchange_id', CHtml::listData(Exchange::model()->findAll(array('order' => 'exchange_name DESC')), 'exchange_id', 'exchange_name')); ?>
        <?php echo $form->error($model->parentContent(), 'exchange_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'company_name'); ?>
        <?php echo $form->textField($model, 'company_name', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'company_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->parentContent(), 'code'); ?>
        <?php echo $form->textField($model->parentContent(), 'code', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model->parentContent(), 'code'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model->parentContent(), 'currency'); ?>
        <?php echo $form->dropDownList($model->parentContent(), 'currency', $this->getCurrencies()); ?>
        <?php echo $form->error($model->parentContent(), 'currency'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- search-form -->