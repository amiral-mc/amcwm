<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Ads") => array('/backend/ads/default/index'),
    AmcWm::t("msgsbase.servers", 'Ad Servers'),
);
$this->sectionName = AmcWm::t("amcTools", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/ads/servers/create'), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_record', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'record_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/ads/default/index'), 'id' => 'records_list', 'image_id' => 'back'),
    ),
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
                'name' => 'server_id',
                'header' => 'ID',
                'htmlOptions' => array('width' => '30'),
            ),
            'server_name',
        )
    ));
    $this->endWidget();
    ?>
</div>