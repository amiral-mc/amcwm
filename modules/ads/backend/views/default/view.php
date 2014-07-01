<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Ads") => array('/backend/ads/default/index'),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = "#{$model->ad_id}";
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array("/backend/ads/default/create"), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array("/backend/ads/default/update", 'id' => $model->ad_id), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array("/backend/ads/default/index"), 'id' => 'admin_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'ad_id',
        array(
            'name' => 'server_id',
            'value' => $model->zone->zone_name,
        ),
        array(
            'name' => 'zone_id',
            'value' => $model->server->server_name,
        ),
        array(
            'name' => 'published',
            'value' => $model->published ? AmcWm::t("msgsbase.core", "Yes") : AmcWm::t("msgsbase.core", "No"),
        ),
    ),
));
