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
    <?php echo CHtml::hiddenField("gid" , $this->gallery->gallery_id);?>
    <div class="row">
        <?php echo $form->label($model, 'video_header'); ?>
        <?php echo $form->textField($model, 'video_header', array('size' => 60, 'maxlength' => 500)); ?>
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