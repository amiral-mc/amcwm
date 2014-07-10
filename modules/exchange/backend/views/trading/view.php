<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Exchange") => array('/backend/exchange/default/index'),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = "#{$model->exchange_id}";
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array("/backend/exchange/trading/create"), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array("/backend/exchange/trading/update", 'id' => $model->exchange_date), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array("/backend/exchange/trading/index"), 'id' => 'admin_list', 'image_id' => 'back'),
    ),
));
//print_r($model->sections->getCurrent()->section_name); exit;
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        array(
            'name' => 'exchange_id',
            'value' => $model->exchange->exchange_name,
        ),
        'exchange_date',
        'trading_value',
        'shares_of_stock',
        'closing_value',
        'difference_value',
        'difference_percentage',
    ),
));
