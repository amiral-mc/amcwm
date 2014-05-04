<div class="wide form">
    <p>
        <?php echo AmcWm::t("amcBack","You may optionally enter a comparison operator (&lt;, &lt;= &gt; ,&gt;=, &lt;&gt; or =) at the beginning of each of your search values to specify how the comparison should be done.");?>
    </p>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
            ));
    ?>    
    <div class="row">
        <?php echo $form->label($model, 'label'); ?>
        <?php echo $form->textField($model, 'label', array('size' => 150, 'maxlength' => 150)); ?>
    </div>
   
    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'published'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'published', array(''=>'', 0=>AmcWm::t("amcFront", "No"),1=>AmcWm::t("amcFront", "Yes"))); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack",'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->