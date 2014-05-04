<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Events") => array('/backend/events/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$this->sectionName = $contentModel->event_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/events/default/create'), 'id' => 'add_poll', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/events/default/update', 'id' => $model->event_id), 'id' => 'edit_event', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/events/default/translate', 'id' => $model->event_id), 'id' => 'translate_event', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/events/default/index'), 'id' => 'events_list', 'image_id' => 'back'),
    ),
));
?>

<?php
$sectionTree = Sections::getSectionTree($model->section_id);
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'event_id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Event Header"),
            'value' => $contentModel->event_header,
            'type' => 'html',
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Event Detail"),
            'value' => $contentModel->event_detail,
            'type' => 'html',
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Section"),
            'value' => Sections::drawSectionPath($model->section_id),
        ),      
        array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcFront", "Yes") : AmcWm::t("amcFront", "No"),
        ),
        array(
            'name' => 'country_code',
            'value' => ($model->country_code) ? $model->country->getCountryName() : NULL,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Location"),
            'value' => $contentModel->location,
            'type' => 'html',
        ),
        array(
            'name' => 'event_date',
            'value' => Yii::app()->dateFormatter->format("yyyy-MM-dd hh:mm", $model->event_date),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Content Lang"),
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
        ),
    ),
));
?>
