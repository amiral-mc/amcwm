<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/default/index'),
    $model->getParentContent()->company->getCurrent()->company_name,
    AmcWm::t("msgsbase.core", "Company Branches"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/directory/branches/create', 'cid' => $this->getCompanyId()), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('cid' => $this->getCompanyId())), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('cid' => $this->getCompanyId())), 'id' => 'delete_glossary', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'translate_branch', 'image_id' => 'translate'),
//        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'glossary_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/directory/default/index'), 'id' => 'glossary_list', 'image_id' => 'back'),
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
                'name' => 'branch_id',
                'htmlOptions' => array('width' => '16'),
            ),
            array(
                'name' => 'branch_name',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", 'Email'),
                'value' => '$data->getParentContent()->email',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", 'Phone'),
                'value' => '$data->getParentContent()->phone',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", 'Mobile'),
                'value' => '$data->getParentContent()->mobile',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", 'Fax'),
                'value' => '$data->getParentContent()->fax',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>