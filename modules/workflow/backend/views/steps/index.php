<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Workflow Steps"),
);

$this->sectionName = AmcWm::t("msgsbase.core", "Manage Workflows");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
//        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/workflow/steps/create', 'mid' => $this->module->module_id), 'id' => 'add_flow', 'image_id' => 'add'),
//        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('mid' => $this->module->module_id)), 'id' => 'edit_flow', 'image_id' => 'edit'),
//        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('mid' => $this->module->module_id)), 'id' => 'delete_flows', 'image_id' => 'delete'),
//        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('mid' => $this->module->module_id)), 'id' => 'publish_flows', 'image_id' => 'publish'),
//        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('mid' => $this->module->module_id)), 'id' => 'unpublish_flows', 'image_id' => 'unpublish'),
//        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'searsh_flows', 'image_id' => 'search'),
        array('label' => AmcWm::t("msgsbase.core", 'Assign'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('mid' => $this->module->module_id)), 'id' => 'assign_flows', 'image_id' => 'assign'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/workflow/default/index'), 'id' => 'polls_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<div class="search-form" style="display:none">
    <?php
//    $this->renderPartial('_search', array(
//        'model' => $model,
//    ));
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
                'name' => 'flow_title',
                'htmlOptions' => array(),
            ),
            array(
                'value' => 'count($data->getSteps())',
                'htmlOptions' => array('width' => '60', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Steps Count'),
            ),
            array(
                'name' => 'enabled',
                'header' => AmcWm::t("msgsbase.core", 'Enabled'),
                'value' => '($data->enabled) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
        ),
    ));
    $this->endWidget();
    ?>

</div>