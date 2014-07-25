<?php
$this->breadcrumbs = array(AmcWm::t("msgsbase.core", "Exchange"));
$this->sectionName = AmcWm::t("msgsbase.tradings", "Exchange Tradings");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/exchange/trading/create', 'eid' => $model->exchange_id), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->exchange_id)), 'id' => 'add_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Preview'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->exchange_id)), 'id' => 'preview_record', 'image_id' => 'view'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'records_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->exchange_id)), 'id' => 'delete_record', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/exchange/default/index'), 'id' => 'admin_list', 'image_id' => 'back'),
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
        'selectableRows' => 2,
        'dataProvider' => $model->search(),
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'header' => AmcWm::t("msgsbase.core", "Exchange ID"),
                'value' => '$data->exchange_id',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
            array(
                'name' => 'trading_value',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
            array(
                'name' => 'shares_of_stock',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
            array(
                'name' => 'closing_value',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
            array(
                'name' => 'difference_value',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
            array(
                'name' => 'difference_percentage',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
            array(
                'name' => 'exchange_date',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
        ),
    ));
    $this->endWidget();
    ?>
</div>