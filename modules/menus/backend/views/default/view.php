<?php

$model = $contentModel->getParentContent();
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/menus/default/create', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId()), 'id' => 'add_section', 'image_id' => 'add');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/menus/default/update', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId(), 'id' => $model->item_id), 'id' => 'edit_item', 'image_id' => 'edit');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/menus/default/translate', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId(), 'id' => $model->item_id), 'id' => 'translate_item', 'image_id' => 'translate');
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Sub Menu Items'), 'url' => array('/backend/menus/default/items', 'mid' => $this->getMenuId(), 'pid' => $model->item_id), 'id' => 'sub_sections', 'image_id' => 'sub-sections');

$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/menus/default/items', 'pid'=>$this->getParentId(), 'mid' => $this->getMenuId()), 'id' => 'modules_list', 'image_id' => 'back');

$this->breadcrumbs[] = AmcWm::t("amcTools", "View");
$this->sectionName = $contentModel->label;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));


$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'item_id',
        'label',
    ),
));