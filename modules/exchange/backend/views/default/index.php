<?php
$this->breadcrumbs = array(AmcWm::t("msgsbase.core", "Exchange"));
$this->sectionName = AmcWm::t("msgsbase.core", "Shares of Stock List");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/exchange/default/create', 'eid' => $model->exchange_id), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->exchange_id)), 'id' => 'add_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Preview'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->exchange_id)), 'id' => 'preview_record', 'image_id' => 'view'),
        array('label' => AmcWm::t("msgsbase.companies", 'Exchange Companies'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'companies', 'refId' => 'eid'), 'id' => 'manage_companies', 'image_id' => 'listing'),
        array('label' => AmcWm::t("msgsbase.tradings", 'Exchange Tradings'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'trading', 'refId' => 'eid'), 'id' => 'manage_tradings', 'image_id' => 'listing'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_ad', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_ad', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'records_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_record', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'admin_list', 'image_id' => 'back'),
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
                'name' => 'exchange_id',
                'htmlOptions' => array('width' => '10', 'align' => 'center'),
            ),
            array(
                'name' => 'exchange_name',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
            array(
                'name' => 'currency',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
            array(
                'name' => 'published',
                'header' => AmcWm::t("msgsbase.core", "Published"),
                'value' => '($data->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
        ),
    ));
    $this->endWidget();
    ?>
</div>