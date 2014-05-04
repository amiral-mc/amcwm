<?php
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Menu Items'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'items', 'refId' => 'mid'), 'id' => 'menu-Items', 'image_id' => 'menuItems');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'modules_list', 'image_id' => 'back');

$this->breadcrumbs[] = AmcWm::t("msgsbase.core", "Menus List");
$this->sectionName = AmcWm::t("msgsbase.core", "Menus List");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
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

    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'data-grid',
        'dataProvider' => $model->search(),
//        'selectableRows' => Yii::app()->params["pageSize"],
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'menu_id',
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'menu_name',
//                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => 'levels',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>