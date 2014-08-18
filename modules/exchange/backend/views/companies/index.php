<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Exchange") => array('/backend/exchange/default/index'),
);

$this->sectionName = AmcWm::t("msgsbase.companies", "Exchange Companies");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/exchange/companies/create', 'eid' => $model->parentContent()->exchange_id), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->parentContent()->exchange_id)), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'View'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->parentContent()->exchange_id)), 'id' => 'view_record', 'image_id' => 'view'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->parentContent()->exchange_id)), 'id' => 'delete_record', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->parentContent()->exchange_id)), 'id' => 'publish_ad', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('eid' => $model->parentContent()->exchange_id)), 'id' => 'unpublish_ad', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'record_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/exchange/default/index'), 'id' => 'records_list', 'image_id' => 'back'),
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
    $dataProvider = $model->parentContent()->search();
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
                'name' => 'exchange_companies_id',
                'htmlOptions' => array('width' => '30'),
            ),
            array(
                'header' => AmcWm::t('msgsbase.companies', 'Company Name'),
                'value' => 'isset($data->getCurrent()->company_name) ? $data->getCurrent()->company_name : AmcWm::t("amcBack", "Not Translated")',            ),
            'code',
            'currency',
            array(
                'name' => 'published',
                'value' => '($data->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>