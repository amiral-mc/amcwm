<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Sections") => array('/backend/sections/default/index'),
);
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_section', 'image_id' => 'save');
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Sub-Sections'), 'url' => array('/backend/sections/default/index', 'sid' => $model->section_id), 'id' => 'sub_sections', 'image_id' => 'sub-sections');
if ($this->getParentId()) {
    $parentSection = $this->getParentSection()->getTranslated($contentModel->content_lang)->section_name;
    $sections = Sections::getTree($this->getParentId());
    $this->breadcrumbs[AmcWm::t("msgsbase.core", "Sections")] = array('/backend/sections/default/index');
    foreach ($sections as $section) {
        $this->breadcrumbs[$section['section_name']] = array('/backend/sections/default/view', 'id' => $section['section_id'], 'sid' => $section['parent_section']);
    }
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/sections/default/index', 'sid' => $this->getParentId()), 'id' => 'modules_list', 'image_id' => 'back');
} else {
    $parentSection = NULL;
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/sections/default/index'), 'id' => 'modules_list', 'image_id' => 'back');
}
$this->breadcrumbs[] = AmcWm::t("amcTools", "Translate");
$this->sectionName = $contentModel->section_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
<div class="form">
    <?php
    $model = $contentModel->getParentContent();
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>
    <?php echo Chtml::hiddenField('sid', $this->getParentId()); ?>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
    <fieldset>                
        <legend><?php echo AmcWm::t("msgsbase.core", "Section data"); ?>:</legend>
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
            <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Published'); ?></span>:
            <span class="translated_org_item">
                <?php
                if ($model->published) {
                    echo AmcWm::t("amcFront", "Yes");
                } else {
                    echo AmcWm::t("amcFront", "No");
                }
                ?>
            </span>

        </div>
        <div class="row">
            </span>
            <?php echo $form->labelEx($translatedModel, 'section_name'); ?>
            <?php echo $form->textField($translatedModel, 'section_name', array('size' => 150, 'maxlength' => 150)); ?>
            <?php echo $form->error($translatedModel, 'section_name'); ?>
        </div>
        <?php if ($model->parent_section): ?>
            <div class="row">
                <span class="translated_label"><?php echo AmcWm::t("msgsbase.core", 'Section Name') ?>;
                </span>
                <span class="translated_org_item">
                    <?php echo $parentSection ?>
                </span>
            </div>
        <?php endif; ?>
         <div class="row">
                <?php echo $form->labelEx($translatedModel, 'description'); ?>
                <?php echo $form->error($translatedModel, 'description'); ?>
                <?php
                $this->widget('amcwm.core.widgets.tinymce.MTinyMce', array(
                    'model' => $translatedModel,
                    'attribute' => 'description',
                    'editorTemplate' => 'full',
                    'htmlOptions' => array(
                        'style' => 'height:300px; width:630px;'
                    ),
                        )
                );
                ?>            
            </div>      
        <?php if ($this->getModule()->appModule->useSupervisor): ?>

            <div class="row">
                <?php echo $form->labelEx($translatedModel, 'supervisor'); ?>
                <?php echo $form->dropDownList($translatedModel, 'supervisor', Persons::getSupervisorsList(AmcWm::t("msgsbase.core", "Without Supervisor"))); ?>
                <?php echo $form->error($translatedModel, 'supervisor'); ?>
            </div>
        <?php endif; ?>

    </fieldset>

    <fieldset>
        <legend><?php echo AmcWm::t("amcBack", "Tags"); ?>:</legend>
        <div class="row">
            <?php
            $this->widget('Keywards', array(
                'model' => $translatedModel,
                'attribute' => "tags[]",
//                    'name' => "tags",
                'values' => $translatedModel->tags,
                'formId' => $formId,
                'container' => "keywordItems",
                'delimiter' => Yii::app()->params["limits"]["delimiter"],
                'elements' => Yii::app()->params["limits"]["elements"], // keyword boxs count
                'wordsCount' => Yii::app()->params["limits"]["wordsCount"], //  words in each box count
                'htmlOptions' => array(),
                    )
            );
            ?>            
        </div>     
    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->