<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Exchange") => array('/backend/exchange/default/index'),
    AmcWm::t("msgsbase.companies", 'Companies') => array('/backend//exchange/companies/index'),
    AmcWm::t("amcTools", "View"),
);
$eid = (int) $_GET['eid'];
$this->sectionName = $model->getCurrent()->company_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend//exchange/companies/update', 'id' => $model->exchange_companies_id, 'eid' => $eid), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend//exchange/companies/index', 'eid' => $eid), 'id' => 'records_list', 'image_id' => 'back'),
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/exchange/companies/create', 'eid' => $model->exchange_id), 'id' => 'add_record', 'image_id' => 'add')
    ),
));
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'exchange_companies_id',
        array(
            'name' => 'company_name',
            'value' => $model->getCurrent()->company_name,
        ),
        array(
            'name' => 'published',
            'value' => $model->published ? AmcWm::t("msgsbase.core", "Yes") : AmcWm::t("msgsbase.core", "No"),
        ),
        'currency',
    ),
));
