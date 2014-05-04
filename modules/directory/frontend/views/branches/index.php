<?php
$breadcrumbs[AmcWm::t("msgsbase.core", "Member Area")] = array('/users/default/index');
$breadcrumbs[AmcWm::t("msgsbase.core", "_manage_company_")] = array('/directory/members/index');
$breadcrumbs[] =  AmcWm::t("msgsbase.core", 'Branches');
$pageContent = $this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/directory/branches/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_glossary', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'translate_branch', 'image_id' => 'translate'),
//        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'glossary_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/directory/members/index'), 'id' => 'view_company', 'image_id' => 'back'),
    ),
), true);
$this->beginClip('clipForm');
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

<?php
$this->endClip('clipForm');
$pageContent.=$this->clips['clipForm'];
$this->widget('PageContentWidget', array(
    'id' => 'siteMap',
    'contentData' => $pageContent,
    'title' => AmcWm::t("msgsbase.core", '_manage_company_'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>