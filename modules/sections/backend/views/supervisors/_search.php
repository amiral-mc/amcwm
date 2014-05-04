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

    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage(), array('id' => 'search_lang')); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam(), array('id' => 'search_module')); ?>
    <div class="row">
        <?php echo $form->label($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 65)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'email'); ?>
        <?php echo $form->textField($model->getParentContent(), 'email', array('size' => 60, 'maxlength' => 65)); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->