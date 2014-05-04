<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Documents"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/documents/default/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_docs', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'docs_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'translate_company', 'image_id' => 'translate'),
        array('label' => AmcWm::t("msgsbase.core", 'Categories'), 'url' => array('/backend/documents/categories/index'), 'id' => 'add_category', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'docs_list', 'image_id' => 'back'),
    ),
));
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'contentModel' => $model,
    ));
    ?>
</div><!-- search-form -->
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
                'value' => '$data->doc_id',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'doc_id',
                'htmlOptions' => array('width' => '16'),
            ),
            'title',
            array(
                'name' => AmcWm::t("msgsbase.core", "Category Name"),
                'value' => '($data->getParentContent()->category_id) ? $data->getParentContent()->category->getCurrent()->category_name : ""',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => 'start_date',
                'htmlOptions' => array('width' => '100'),
                'value' => 'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$data->getParentContent()->start_date)',
                'header' => AmcWm::t("msgsbase.core", 'Start Date'),
            ),
            array(
                'name' => 'end_date',
                'htmlOptions' => array('width' => '100'),
                'value' => '($data->getParentContent()->end_date)?Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $data->getParentContent()->end_date):""',
                'header' => AmcWm::t("msgsbase.core", 'End Date'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->getParentContent()->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Published'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>