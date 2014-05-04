<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Glossary"),
);
$this->sectionName = AmcWm::t("amcTools", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/glossary/default/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_glossary', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'translate_exp', 'image_id' => 'translate'),
//        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'glossary_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("msgsbase.core", 'Categories'), 'url' => array('/backend/glossary/categories/index'), 'id' => 'add_category', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'glossary_list', 'image_id' => 'back'),
    ),
));
?>

<div class="form">
    <?php
    $this->beginWidget('CActiveForm', array(
        'id' => Yii::app()->params["adminForm"],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));

    $dataProvider = $model->search();
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
                'name' => 'expression_id',
                'htmlOptions' => array('width' => '16'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", "Expression"),
                'value'=>'$data->getParentContent()->expression',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => 'meaning',
                'htmlOptions' => array(),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>