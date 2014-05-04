<div class="wide form">

    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
            ));
    ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'category_id'); ?>
        <?php echo $form->dropDownList($model, 'category_id', DocsCategories::getCategoriesList(), array("prompt"=>"--")); ?>
    </div>

    <div class="row">
        <?php echo $form->label($contentModel, 'title'); ?>
        <?php echo $form->textField($contentModel, 'title', array('size' => 60, 'maxlength' => 65)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($contentModel, 'content_lang'); ?>
        <?php echo $form->dropDownList($contentModel, 'content_lang', $this->getLanguages(), array('empty' => '')); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->