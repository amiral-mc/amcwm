<?php

$model = $contentModel->getParentContent();
$options = null;
$allOptions = $this->module->appModule->options;
if ($model->category) {
    $options = CJSON::decode($model->category->settings);
}
if (!$options) {
    $options = $allOptions['default'];
}
$mediaSettings = AmcWm::app()->appModule->mediaSettings;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/default/index'),
    AmcWm::t("msgsbase.core", "Requests") => array('/backend/directory/requests/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$this->sectionName = $contentModel->company_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/directory/requests/update', 'id' => $model->company_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => (AmcWm::app()->request->getParam('f')) ? array('/backend/directory/default/index') : array('/backend/directory/requests/index'), 'id' => 'companies_list', 'image_id' => 'back'),
    ),
));




$drawImage = NULL;
if ($model->company_id && $model->image_ext) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['images']['path'] . "/" . $model->company_id . "." . $model->image_ext))) {
        $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['images']['path'] . "/" . $model->company_id . "." . $model->image_ext . "?" . time(), "", array("class" => "image", "style" => "max-width:250px;")) . '</div>';
    }
}
$drawLink = null;
if ($model->company_id && $model->file_ext && is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['attach']['path'] . "/" . $model->company_id . "." . $model->file_ext))) {
    $drawLink = '<a href="' . $this->createUrl('/site/download', array('f' => "{$mediaSettings['paths']['attach']['path']}/{$model->company_id}.{$model->file_ext}")) . '">' . AmcWm::t("msgsbase.core", 'Download file') . '</a>';
}

$staus = $model->getStatus();
$drawMap = null;
if ($options['check']['mapEnable']) {
    $mapsData = null;
    $zoom = 1;
    $lat = null;
    $lng = null;
    $enabled = false;
    if ($model->maps) {
        $mapsData = CJSON::decode((string) $model->maps);
        $zoom = (isset($mapsData['location']['zoom'])) ? $mapsData['location']['zoom'] : $zoom;
        $lat = (isset($mapsData['location']['lat'])) ? $mapsData['location']['lat'] : $lat;
        $enabled = (isset($mapsData['location']['enabled'])) ? $mapsData['location']['enabled'] : $enabled;
        $lng = (isset($mapsData['location']['lng'])) ? $mapsData['location']['lng'] : $lng;
    }
    if ($enabled && $lat) {
        $js = " var marker = null;
            function initialize() {
                var mapOptions = {
                  zoom: {$zoom},
                  center: new google.maps.LatLng({$lat}, {$lng}),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                var map = new google.maps.Map(document.getElementById('map-canvas'),
                    mapOptions);

                var selectedLocation = new google.maps.LatLng({$lat}, {$lng});
                marker = new google.maps.Marker({
                            position: selectedLocation,
                            map: map
                      });
                map.panTo(selectedLocation);
            }
      google.maps.event.addDomListener(window, 'load', initialize);";

        Yii::app()->getClientScript()
                ->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', CClientScript::POS_HEAD)
                ->registerScript('__GMAP', $js, CClientScript::POS_READY);

        $drawMap = null;
        if ($model->company_id && isset($mapsData['image'])) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['maps']['path'] . "/" . $model->company_id . "." . $mapsData['image']))) {
                $drawMap .= '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['maps']['path'] . "/" . $model->company_id . "." . $mapsData['image'] . "?" . time(), "", array("class" => "image", "style" => "max-width:100px")) . '</div>';
            }
        }
        $drawMap .= '<br />';
        $drawMap .= '<div id="map-canvas" style="width: 100%; height: 300px;"></div>';
    }
}
$attributes[] = 'company_id';
$attributes[] = array(
    'label' => AmcWm::t("msgsbase.core", 'Status'),
    'value' => $staus[$model->accepted],
);
$attributes[] = array(
    'label' => AmcWm::t("msgsbase.core", 'In Ticker'),
    'value' => ($model->in_ticker) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
    'visible' => $options['check']['useTicker'],
);
$category = null;
if($model->category !== null){
    $category = $model->category->getCurrent()->category_name;
}
$attributes[] = array(
    'name' => 'category_id',
    'value'=> $category,
    'visible' => $allOptions['system']['check']['categoriesEnable'],
);
$attributes[] = array(
    'name' => AmcWm::t("msgsbase.core", "Name"),
    'value' => $contentModel->company_name,
);
$attributes[] = array(
    'name' => AmcWm::t("msgsbase.core", "Company Activity"),
    'value' => $contentModel->activity,
);
$attributes[] = array(
    'name' => AmcWm::t("msgsbase.core", "Nationality"),
    'value' => ($contentModel->getParentContent()->nationality) ? Yii::app()->getController()->getCountries(0, $contentModel->getParentContent()->nationality) : "",
);
$attributes = array_merge($attributes, $contentModel->getExtendedAttributeViewValues("company_address"));
$attributes[] = array(
    'name' => AmcWm::t("msgsbase.core", "City"),
    'value' => $contentModel->city,
);
$attributes = array_merge($attributes, $model->getExtendedAttributeViewValues("email"));
$attributes = array_merge($attributes, $model->getExtendedAttributeViewValues("url"));
$attributes = array_merge($attributes, $model->getExtendedAttributeViewValues("phone"));
$attributes = array_merge($attributes, $model->getExtendedAttributeViewValues("mobile"));
$attributes = array_merge($attributes, $model->getExtendedAttributeViewValues("fax"));
$attributes[] = array(
    'label' => AmcWm::t("msgsbase.core", "Description"),
    'type' => 'html',
    'value' => $contentModel->description,
);
$attributes[] = array(
    'name' => 'imageFile',
    'type' => 'html',
    'visible' => $options['check']['imageEnable'],
    'value' => ($model->image_ext) ? $drawImage : AmcWm::t("amcBack", "No"),
);
$attributes[] = array(
    'name' => 'attachFile',
    'type' => 'html',
    'visible' => $options['check']['attachEnable'],
    'value' => ($model->file_ext) ? $drawLink : AmcWm::t("amcBack", "No"),
);
$attributes[] = array(
    'name' => 'mapFile',
    'type' => 'raw',
    'visible' => $options['check']['mapEnable'],
    'value' => $drawMap,
);

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => $attributes)
);
?>