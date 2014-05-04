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
        <?php echo $form->label($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 45, 'maxlength' => 45)); ?>
    </div>
    
   <div class="row">
        <?php echo $form->label($model, 'ip'); ?>
        <?php echo $form->textField($model, 'ip', array('size' => 45, 'maxlength' => 45)); ?>
    </div> 

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack",'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->