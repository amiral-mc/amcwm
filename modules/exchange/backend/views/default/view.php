<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Exchange") => array('/backend/exchange/default/index'),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = "#{$model->exchange_id}";
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array("/backend/exchange/default/create"), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array("/backend/exchange/default/update", 'id' => $model->exchange_id), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array("/backend/exchange/default/index"), 'id' => 'admin_list', 'image_id' => 'back'),
    ),
));
//print_r($model->sections->getCurrent()->section_name); exit;
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'exchange_id',
        'exchange_name',
        'currency',
//        array(
//            'name' => 'exchange_id',
//        ),
//        array(
//            'name' => 'index',
////            'value' => $model->server->server_name,
//        ),
//        array(
//            'name' => 'percentage',
////            'value' => $model->section_name,
//        ),
//        array(
//            'name' => 'net',
////            'value' => $model->published ? AmcWm::t("msgsbase.core", "Yes") : AmcWm::t("msgsbase.core", "No"),
//        ),
    ),
));
