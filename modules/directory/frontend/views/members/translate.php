<?php
$model = $contentModel->getParentContent();
$options = null;
$allOptions = $this->module->appModule->options;
if ($model->category) {
    $options = CJSON::decode($model->category->settings);
}
if (!$options) {
    $options = $allOptions['default'];
}
$formId = Yii::app()->params["adminForm"];

$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'translate_company', 'image_id' => 'save');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/directory/members/index'), 'id' => 'view_company', 'image_id' => 'back');
$breadcrumbs[AmcWm::t("msgsbase.core", "Member Area")] = array('/users/default/index');
$breadcrumbs[AmcWm::t("msgsbase.core", "_manage_company_")] = array('/directory/members/index');
$breadcrumbs[] = AmcWm::t("amcTools", "Translate");
$pageContent = $this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
), true);
$this->beginClip('clipForm');
?>
<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('Form', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
    <fieldset>                
        <legend></legend>
        <div class="row">
            <span class="translated_label">
                <?php echo AmcWm::t("msgsbase.core", "Content Lang"); ?>
            </span>
            :
            <span class="translated_org_item">
                <?php echo Yii::app()->params['languages'][$contentModel->content_lang]; ?>
            </span>
        </div>     
        <div class="row">
            <?php
            $actionParams = $this->getActionParams();
            if (array_key_exists('tlang', $actionParams)) {
                unset($actionParams['tlang']);
            }
            $translateRoute = Html::createUrl($this->getRoute());
            ?> 
            <?php echo CHtml::label(AmcWm::t("amcTools", "Translate To"), "tlang") ?>
            <?php echo CHtml::dropDownList("tlang", $translatedModel->content_lang, $this->getTranslationLanguages(), array("onchange" => "FormActions.translationChange('$translateRoute', " . CJSON::encode($actionParams) . ");")); ?>
        </div>       
        <div class="row">
            </span>
            <?php echo $form->labelEx($translatedModel, 'company_name'); ?>
            <?php echo $form->textField($translatedModel, 'company_name', array('size' => 60, 'maxlength' => 100)); ?>
            <?php echo $form->error($translatedModel, 'company_name'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'activity'); ?>
            <?php echo $form->textField($translatedModel, 'activity', array('size' => 60, 'maxlength' => 255)); ?>
            <?php echo $form->error($translatedModel, 'activity'); ?>
        </div>
        <div class="row">
            </span>
            <?php echo $form->labelEx($translatedModel, 'company_address'); ?>
            <?php echo $form->extendableField($translatedModel, 'company_address', 'textField', array('translateOnly' => true, 'htmlOptions' => array('size' => 60, 'maxlength' => 150))); ?>
            <?php echo $form->error($translatedModel, 'company_address'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'city'); ?>
            <?php echo $form->textField($translatedModel, 'city', array('size' => 60, 'maxlength' => 150)); ?>
            <?php echo $form->error($translatedModel, 'city'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'description'); ?>
            <?php echo $form->error($translatedModel, 'description'); ?>
            <?php echo $form->richTextField($translatedModel, 'description', array('editorTemplate' => 'full', 'height' => '300px', "width" => "630px")); ?>           
        </div>                
    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$this->endClip('clipForm');
$pageContent.=$this->clips['clipForm'];
$this->widget('PageContentWidget', array(
    'id' => 'translae_company',
    'contentData' => $pageContent,
    'title' => AmcWm::t("msgsbase.core", '_manage_company_'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>