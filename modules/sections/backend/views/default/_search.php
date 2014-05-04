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
        <?php echo $form->label($model, 'section_name'); ?>
        <?php echo $form->textField($model, 'section_name', array('size' => 150, 'maxlength' => 150)); ?>
    </div>
    <?php if($this->getModule()->appModule->useSupervisor):?>
    <div class="row">
        <?php echo $form->label($model, 'supervisor'); ?>
        <?php echo $form->dropDownList($model, 'supervisor', Persons::getSupervisorsList(AmcWm::t("msgsbase.core", "Without Supervisor"))); ?>
    </div>
    <?php endif;?>
    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'published'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'published', array(''=>'', 0=>AmcWm::t("amcFront", "No"),1=>AmcWm::t("amcFront", "Yes"))); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack",'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->