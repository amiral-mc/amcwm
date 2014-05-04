<?php
$formId = Yii::app()->params["adminForm"];

$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_menuItem', 'image_id' => 'save');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/menus/default/items', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId()), 'id' => 'items_list', 'image_id' => 'back');
$this->breadcrumbs[$this->getMenuData()->menu_name] = array('/backend/menus/default/index');

if ($this->getParentId()) {
    $menuItems = MenuItems::getTree($this->getParentId(), $this->getMenuId());
    foreach ($menuItems as $menuItem) {
        $this->breadcrumbs[$menuItem['label']] = array('/backend/menus/default/view', 'id' => $menuItem['item_id'], 'pid' => $menuItem['parent_item'], 'mid' => $menuItem['menu_id']);
    }    
} else {
    $this->breadcrumbs[] = AmcWm::t("msgsbase.core", "Menu Items");
}

$this->sectionName = AmcWm::t("amcTools", "Create");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>