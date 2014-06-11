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
        <?php echo $form->label($model, 'article_header'); ?>
        <?php echo $form->textField($model, 'article_header', array('size' => 100, 'maxlength' => 100)); ?>
    </div>   

    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'published'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'published', array('' => '', 0 => AmcWm::t("amcFront", "No"), 1 => AmcWm::t("amcFront", "Yes"))); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'country_code'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'country_code', $this->getCountries(true)); ?>
    </div>
    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'section_id'); ?>
        <?php echo $form->dropDownList($model->getParentContent(), 'section_id', Sections::getSectionsList(), array('empty' => Yii::t('zii', 'Not set'))); ?>
    </div>
    <div class="row">
        <?php echo $form->label($model->getParentContent(), 'writer_id'); ?>
        <?php
        $this->widget('amcwm.core.widgets.select2.ESelect2', array(
            'model' => $model->getParentContent(),
            'attribute' => "writer_id",
            'options' => array(
                "dropdownCssClass" => "bigdrop",
                "placeholder" => AmcWm::t('amcTools', 'Enter Search Keywords'),
                'minimumInputLength' => '1',
                'ajax' => array(
                    'dataType' => "json",
                    "quietMillis" => 100,
                    'url' => Html::createUrl('/backend/articles/default/ajax', array('do' => 'findWriters', 'prompt'=> AmcWm::t("amcTools", "All"))),
                    'data' => 'js:function (term, page) { // page is the one-based page number tracked by Select2
                        return {
                               q: term, //search term
                               page: page, // page number                     
                           };
                       }',
                    'results' => 'js:function (data, page) {
                            var more = (page * ' . Writers::REF_PAGE_SIZE . ') < data.total; // whether or not there are more results available 
                            // notice we return the value of more so Select2 knows if more results can be loaded
                            return {results: data.records, more: more};
                          }',
                ),
            ),
            'htmlOptions' => array(
                'style' => 'min-width:400px;',
            ),
        ));
        ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->