<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Exchange") => array('/backend/exchange/default/index'),
    AmcWm::t("amcTools", "Create"),
);

$this->sectionName = AmcWm::t("amcTools", "Create");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId, 'params' => array('eid' => $eid)), 'id' => 'edit_record', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array("/backend/exchange/trading/index", 'eid' => $eid), 'id' => 'admin_list', 'image_id' => 'back'),
    ),
));
$this->renderPartial('_form', array('model' => $model, 'formId' => $formId, 'companies' => $companies, 'tradingsModel' => $tradingsModel, 'eid' => $eid));
?>