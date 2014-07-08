<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Exchange") => array('/backend/exchange/default/index'),
    AmcWm::t("msgsbase.companies", 'Companies') => array('/backend//exchange/companies/index'),
    AmcWm::t("amcTools", "View"),
);

$this->sectionName = $model->company_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend//exchange/companies/update', 'id' => $model->company_id), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend//exchange/companies/index'), 'id' => 'records_list', 'image_id' => 'back'),
    ),
));
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'company_id',
        'company_name',
        array(
            'name' => 'published',
            'value' => $model->published ? AmcWm::t("msgsbase.core", "Yes") : AmcWm::t("msgsbase.core", "No"),
        ),
    ),
));
