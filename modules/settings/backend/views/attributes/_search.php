<div class="wide form">
    <p>
        <?php echo AmcWm::t("amcBack","You may optionally enter a comparison operator (&lt;, &lt;= &gt; ,&gt;=, &lt;&gt; or =) at the beginning of each of your search values to specify how the comparison should be done.");?>
    </p>
    <?php
        $attributesTypes = $this->module->appModule->settings['attributesTypes'];

    $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
            ));
    ?>
    
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage(), array('id' => 'search_lang')); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam(), array('id' => 'search_module')); ?>
    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'attribute_type'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'attribute_type', $attributesTypes, array('prompt' => AmcWm::t("msgsbase.core", 'Select Attribute Type'))); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'label'); ?>
        <?php echo $form->textField($model, 'label', array('size' => 100, 'maxlength' => 100)); ?>
    </div>   
   
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack",'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->