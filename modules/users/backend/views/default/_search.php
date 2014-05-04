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
        <?php echo $form->label($model->person->getCurrent(), 'name'); ?>
        <?php echo $form->textField($model->person->getCurrent(), 'name', array('size' => 60, 'maxlength' => 65)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model->person, 'email'); ?>
        <?php echo $form->textField($model->person, 'email', array('size' => 60, 'maxlength' => 65)); ?>
    </div>
    <div class="row">
        <?php echo $form->label($model, 'username'); ?>
        <?php echo $form->textField($model, 'username', array('size' => 30, 'maxlength' => 30)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'published'); ?>
        <?php echo $form->dropDownList($model, 'published', array('' => '', 0 => AmcWm::t("amcFront", "No"), 1 => AmcWm::t("amcFront", "Yes"))); ?>
    </div>    
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->