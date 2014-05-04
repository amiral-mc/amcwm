<?php
$mediaPaths = Persons::getSettings()->mediaPaths;
$model = $contentModel->getParentContent();
$user = $model->users;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Users") => array('/backend/users/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$this->sectionName = $user->username;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/users/default/create'), 'id' => 'add_user', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/users/default/update', 'id' => $model->person_id), 'id' => 'edit_user', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.core", 'Permissions'), 'url' => array('/backend/users/default/permissions', 'id' => $model->person_id), 'id' => 'user_permissions', 'image_id' => 'permissions'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/users/default/translate', 'id' => $model->person_id), 'id' => 'translate_person', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/users/default/index'), 'id' => 'users_list', 'image_id' => 'back'),
    ),
));
?>

<?php

$drawImage = AmcWm::t("amcBack", "No");
if ($model->person_id && $model->thumb) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" .$mediaPaths['thumb']['path'] . "/" . $model->person_id . "." . $model->thumb))) {
        $drawImage = CHtml::image(Yii::app()->baseUrl . "/" .$mediaPaths['thumb']['path'] . "/" . $model->person_id . "." . $model->thumb . "?" . time(), "", array("class" => "image", "width" => "60"));
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
            'label' => AmcWm::t("msgsbase.core", 'Username'),
            'value' => ($user->username)
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Role'),
            'value' => (($user->role)) ? $user->role->role : NULL,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Published'),
            'type' => 'html',
            'value' => ($user->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0)),
        ),
        array(
            'name' => 'content_lang',
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
        ),
    ),
));
?>
