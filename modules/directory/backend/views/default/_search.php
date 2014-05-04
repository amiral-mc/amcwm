<div class="wide form">

    <?php 
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
            ));
    ?>

     <?php if ($allOptions['system']['check']['categoriesEnable']): ?>
            <div class="row">
            <?php echo $form->labelEx($model, 'category_id'); ?>
            <?php echo $form->dropDownList($model, 'category_id', $model->getCategories(), array('empty'=>'')); ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <?php echo $form->labelEx($contentModel, 'company_name'); ?>
        <?php echo $form->textField($contentModel, 'company_name', array('size' => 60, 'maxlength' => 100)); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'nationality'); ?>
        <?php echo $form->dropDownList($model, 'nationality', $this->module->appModule->getNationality(true), array('prompt' => '')); ?>
    </div>

    <div class="row">
        <?php echo $form->label($contentModel, 'content_lang'); ?>
        <?php echo $form->dropDownList($contentModel, 'content_lang', $this->getLanguages(), array('empty'=>'')); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(AmcWm::t("amcBack", 'Search')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->