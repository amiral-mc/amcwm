<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);

$this->sectionName = $contentModel->gallery_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Create'), 'url' => array('/backend/multimedia/default/create'), 'id' => 'add_gallery', 'image_id' => 'add'),
        array('label' => AmcWm::t("msgsbase.core", 'Edit'), 'url' => array('/backend/multimedia/default/update', 'id' => $model->gallery_id), 'id' => 'edit_gallery', 'image_id' => 'edit'),
        array('label' =>AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/multimedia/default/translate', 'id' => $model->gallery_id), 'id' => 'translate_gallery', 'image_id' => 'translate'),
        array('label' => AmcWm::t("msgsbase.core", 'Videos'), 'url' => array('/backend/multimedia/default/videos', 'gid' => $model->gallery_id), 'id' => 'manage_videos', 'image_id' => 'videos'),
        array('label' => AmcWm::t("msgsbase.core", 'Images'), 'url' => array('/backend/multimedia/default/images', 'gid' => $model->gallery_id), 'id' => 'manage_images', 'image_id' => 'images'),
        array('label' => AmcWm::t("msgsbase.core", 'Backgrounds'), 'url' => array('/backend/multimedia/default/backgrounds', 'gid' => $model->gallery_id), 'id' => 'manage_backgrounds', 'image_id' => 'backgrounds'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/multimedia/default/index'), 'id' => 'galleries_list', 'image_id' => 'back'),
    ),
));
?>

<?php

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'gallery_id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Gallery Header"),
            'value' => $contentModel->gallery_header,
        ),
        array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Section"),
            'value' => Sections::drawSectionPath($model->section_id),
        ),
        array(
            'name' => 'country_code',
            'value' => ($model->country_code) ? $model->countryCode->getCountryName() : NULL,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Tags"),
            'value' => nl2br($contentModel->tags),
            'type' => 'html',
        ),
    ),
));
?>
