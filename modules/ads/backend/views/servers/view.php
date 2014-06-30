<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Ads") => array('/backend/ads/default/index'),
    AmcWm::t("msgsbase.servers", 'Ad Servers') => array('/backend//ads/servers/index'),
    AmcWm::t("amcTools", "View"),
);

$this->sectionName = $contentModel->server_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend//ads/servers/update', 'id' => $model->carrier_id), 'id' => 'edit_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend//ads/servers/translate', 'id' => $model->carrier_id), 'id' => 'translate_record', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend//ads/servers/index'), 'id' => 'records_list', 'image_id' => 'back'),
    ),
));
$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'carrier_id',
        'server_name',
    ),
));