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
    <?php
    echo CHtml::hiddenField("gid", $this->gallery->gallery_id);
    ?>
    <div class="row">
        <?php echo $form->label($model, 'image_header'); ?>
        <?php echo $form->textField($model, 'image_header', array('size' => 60, 'maxlength' => 255)); ?>
    </div>
    <div class="row">
        <?php echo $form->label($model, 'published'); ?>
        <?php echo $form->dropDownList($model, 'published', array('' => '', 0 => AmcWm::t("amcBack", "No"), 1 => AmcWm::t("amcBack", "Yes"))); ?>
    </div>      

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->