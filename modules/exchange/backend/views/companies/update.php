<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Exchange") => array('/backend/exchange/default/index'),
    AmcWm::t("msgsbase.companies", 'Exchange Companies') => array('/backend//exchange/companies/index'),
    AmcWm::t("amcTools", "Edit"),
);
$eid = (int) $_GET['eid'];
$this->sectionName = $contentModel->parentContent()->exchange_companies_id;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_record', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend//exchange/companies/index', 'eid' => $eid), 'id' => 'records_list', 'image_id' => 'back'),
    ),
));
$this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId));
?>