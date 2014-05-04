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
    
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage(), array('id' => 'search_lang')); ?>
    <?php echo CHtml::hiddenField('module', Data::getForwardModParam(), array('id' => 'search_module')); ?>
    <div class="row">
        <?php echo $form->label($model, 'article_header'); ?>
        <?php echo $form->textField($model, 'article_header', array('size' => 100, 'maxlength' => 100)); ?>
    </div>   

    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'published'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'published', array(''=>'', 0=>AmcWm::t("amcFront", "No"),1=>AmcWm::t("amcFront", "Yes"))); ?>
    </div>

        <div class="row">
        <?php echo $form->label($model->getParentContent(), 'country_code'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'country_code', $this->getCountries(true)); ?>
    </div>
    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'section_id'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'section_id', Sections::getSectionsList(), array('empty' => Yii::t('zii', 'Not set'))); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack",'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->