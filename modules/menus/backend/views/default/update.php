<?php

$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];

$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_item', 'image_id' => 'save');
if($this->menuData->checkLevel($model->item_id)){
    $toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Sub Menu Items'), 'url' => array('/backend/menus/default/items', 'pid' => $model->item_id, 'mid' => $this->getMenuId()), 'id' => 'sub_items', 'image_id' => 'sub-sections');
}

$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/menus/default/translate', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId(), 'id' => $model->item_id), 'id' => 'translate_items', 'image_id' => 'translate');

$this->breadcrumbs[$this->getMenuData()->menu_name] = array('/backend/menus/default/index');

if ($this->getParentId()) {
    $menuItems = MenuItems::getTree($this->getParentId(), $this->getMenuId());
    foreach ($menuItems as $menuItem) {
        $this->breadcrumbs[$menuItem['label']] = array('/backend/menus/default/items', 'id' => $menuItem['item_id'], 'pid' => $menuItem['parent_item'], 'mid' => $menuItem['menu_id']);
    }
}

$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/menus/default/items', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId()), 'id' => 'items_list', 'image_id' => 'back');
$this->breadcrumbs[] = AmcWm::t("amcTools", "Edit");
$this->sectionName = $contentModel->label;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>