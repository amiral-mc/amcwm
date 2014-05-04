<?php
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/menus/default/create', 'pid' => $this->getParentId(), 'mid' => $this->getMenuId()), 'id' => 'add_menuitems', 'image_id' => 'add');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('pid' => $this->getParentId(), 'mid' => $this->getMenuId())), 'id' => 'edit_menu', 'image_id' => 'edit');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('pid' => $this->getParentId(), 'mid' => $this->getMenuId())), 'id' => 'delete_menus', 'image_id' => 'delete');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('pid' => $this->getParentId(), 'mid' => $this->getMenuId())), 'id' => 'publish_menus', 'image_id' => 'publish');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('pid' => $this->getParentId(), 'mid' => $this->getMenuId())), 'id' => 'unpublish_menus', 'image_id' => 'unpublish');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'docs_search', 'image_id' => 'search');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('pid' => $this->getParentId(), 'mid' => $this->getMenuId())), 'id' => 'translate_menus', 'image_id' => 'translate');
if($this->menuData->checkLevel($this->getParentId())){
    $toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Sub Menu Items'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'items', 'refId' => 'pid', 'params' => array('mid' => $this->getMenuId())), 'id' => 'sub_items', 'image_id' => 'sub-sections');
}
$this->breadcrumbs[$this->getMenuData()->menu_name] = array('/backend/menus/default/index');

if ($this->getParentId()) {
    $menuitems = MenuItems::getTree($this->getParentId(), $this->getMenuId());
    foreach ($menuitems as $menuItem) {
        $this->breadcrumbs[$menuItem['label']] = array('/backend/menus/default/view', 'id' => $menuItem['item_id'], 'pid' => $menuItem['parent_item'], 'mid' => $menuItem['menu_id']);
    }
    if (count($menuitems) > 1) {
        $prevItem = $menuitems[count($menuitems) - 1];
        $backItem = $prevItem['parent_item'];
    } else {
        $backItem = 0;
    }
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/menus/default/items', 'pid'=>$backItem, 'mid' => $this->getMenuId()), 'id' => 'modules_list', 'image_id' => 'back');
} else {
    $this->breadcrumbs[] = AmcWm::t("msgsbase.core", "Menu Items");
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/menus/default/index'), 'id' => 'modules_list', 'image_id' => 'back');
}

$dataProvider = $model->search();
$this->sectionName = AmcWm::t("msgsbase.core", "Manage Menu Items");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => Yii::app()->params["adminForm"],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));


    $dataProvider->pagination->pageSize = Yii::app()->params["pageSize"];
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'data-grid',
        'dataProvider' => $dataProvider,
        'selectableRows' => Yii::app()->params["pageSize"],
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'item_id',
                'htmlOptions' => array('width' => '50', 'align'=>'center'),
            ),
            array(
                'name' => 'label',
//                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => 'content_lang',
                'value' => '($data->content_lang) ? Yii::app()->params["languages"][$data->content_lang] : ""',
                'htmlOptions' => array('width' => '50', 'align'=>'center'),
            ),
            array(
                'value' => '($data->getParentContent()->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'header' => AmcWm::t("msgsbase.core", 'Published'),
                'htmlOptions' => array('width' => '50', 'align'=>'center'),
            ),
                array(
                    'class' => 'CButtonColumn',
                    'template' => '{up} {down}',
                    'buttons' => array(
                        'up' => array(
                            'label' => 'up',
                            'imageUrl' => Yii::app()->request->baseUrl . '/images/up.gif',
                            'url' => 'Html::createUrl("/backend/menus/default/sort", array("id" => $data->item_id, "pid"=>$data->getParentContent()->parent_item, "mid"=>$data->getParentContent()->menu_id, "direction" => "up",))',
                        ),
                        'down' => array(
                            'label' => 'down',
                            'imageUrl' => Yii::app()->request->baseUrl . '/images/down.gif',
                            'url' => 'Html::createUrl("/backend/menus/default/sort", array("id" => $data->item_id, "pid"=>$data->getParentContent()->parent_item, "mid"=>$data->getParentContent()->menu_id,"direction" => "down",))',
                        ),
                    ),
                    'htmlOptions' => array('width' => '40', 'align' => 'center', 'class' => 'dataGridLinkCol'),
                    'header' => AmcWm::t("msgsbase.core", 'Sort'),
                ),
        )
    ));
    $this->endWidget();
    ?>
</div>