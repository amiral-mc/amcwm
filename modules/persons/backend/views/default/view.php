<?php
$model = $contentModel->getParentContent();
$mediaPaths = Persons::getSettings()->mediaPaths;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Persons") => array('/backend/persons/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$this->sectionName = $contentModel->name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/persons/default/create'), 'id' => 'add_user', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/persons/default/update', 'id' => $model->person_id), 'id' => 'edit_user', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/persons/default/translate', 'id' => $model->person_id), 'id' => 'translate_person', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/persons/default/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));
?>

<?php

$drawImage = AmcWm::t("amcBack", "No");
if ($model->person_id && $model->thumb) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaPaths['thumb']['path'] . "/" . $model->person_id . "." . $model->thumb))) {
        $drawImage = CHtml::image(Yii::app()->baseUrl . "/" . $mediaPaths['thumb']['path'] . "/" . $model->person_id . "." . $model->thumb . "?" . time(), "", array("class" => "image", "width" => "60"));
    }
}

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'person_id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Person Image"),
            'type' => 'html',
            'value' => $drawImage,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Name'),
            'value' => $contentModel->name,
        ),
        array(
            'name' => 'sex',
            'value' => $model->getSexLabel(),
        ),
        'email',
        array(
            'name' => 'country_code',
            'value' => $model->country->getCountryName(),
        ),         
        array(
            'name' => 'content_lang',
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
        ),
    ),
));
?>
