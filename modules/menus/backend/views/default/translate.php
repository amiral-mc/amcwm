<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Sub Menu Items") => array('/backend/menus/default/items'),
);
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_items', 'image_id' => 'save');
if($this->menuData->checkLevel($model->item_id)){
    $toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Sub Menu Items'), 'url' => array('/backend/menus/default/items', 'pid' => $model->item_id, 'mid' => $model->menu_id), 'id' => 'sub_menuitems', 'image_id' => 'sub-sections');
}
if ($this->getParentId()) {
    $menuItems = MenuItems::getTree($this->getParentId(), $this->getMenuId());
    $parentItem = $this->getParentItem()->getTranslated($contentModel->content_lang)->label;
    $this->breadcrumbs[AmcWm::t("msgsbase.core", "Menu Items")] = array('/backend/menus/default/items');
    foreach ($menuItems as $menuItem) {
        $this->breadcrumbs[$menuItem['label']] = array('/backend/menus/default/view', 'id' => $menuItem['item_id'], 'pid' => $menuItem['parent_item'], 'mid' => $menuItem['menu_id']);
    }
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/menus/default/items', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId()), 'id' => 'modules_list', 'image_id' => 'back');
} else {
    $parentItem = NULL;
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/menus/default/items', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId()), 'id' => 'modules_list', 'image_id' => 'back');
}
$this->breadcrumbs[] = AmcWm::t("amcTools", "Translate");
$this->sectionName = $contentModel->label;
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
    <?php echo Chtml::hiddenField('pid', $this->getParentId()); ?>
    <?php echo $form->errorSummary(array($model, $translatedModel)); ?>
    <fieldset>                
        <legend><?php echo AmcWm::t("msgsbase.core", "Menu Item data"); ?>:</legend>
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
        <div>
            <?php
            echo AmcWm::t("msgsbase.core", "Title in {language}", array('{language}' => Yii::app()->params['languages'][$contentModel->content_lang]));
            echo " : ", $contentModel->label;
            ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($translatedModel, 'label'); ?>
            <?php echo $form->textField($translatedModel, 'label', array('size' => 150, 'maxlength' => 150)); ?>
            <?php echo $form->error($translatedModel, 'label'); ?>
        </div>
        <?php if ($model->parent_item): ?>
            <div class="row">
                <span class="translated_label">
                    <?php echo AmcWm::t("msgsbase.core", 'Parent Item') ?>;
                </span>
                <span class="translated_org_item">
                    <?php echo $parentItem ?>
                </span>
            </div>
        <?php endif; ?>
    </fieldset>
    <?php $this->endWidget(); ?>

</div><!-- form -->