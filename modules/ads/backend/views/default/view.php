<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Parcels") => array('/backend/ads/default/index'),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = "#{$model->parcel_id}";
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Print'), 'target' => "_blank", 'url' => array("/ads/default/view", 'id' => $model->parcel_id), 'id' => 'print', 'image_id' => 'print'),
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array("/backend/ads/default/create"), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array("/backend/ads/default/update", 'id' => $model->parcel_id), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array("/backend/ads/default/index"), 'id' => 'admin_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'label' => AmcWm::t("msgsbase.core", 'ID'),
        'value' => $model->id),
));
