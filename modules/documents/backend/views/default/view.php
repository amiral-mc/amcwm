<?php

$mediaSettings = $this->module->appModule->mediaSettings;
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Documents") => array('/backend/documents/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$this->sectionName = $contentModel->title;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/documents/default/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/documents/default/update', 'id' => $model->doc_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/documents/default/translate', 'id' => $model->doc_id), 'id' => 'translate_company', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/documents/default/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));


$drawDocLink = NULL;


if ($model->doc_id && $model->file_ext && is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['files']['path'] . "/" . $model->doc_id . "." . $model->file_ext))) {
    $drawDocLink = '<a href="' . $this->createUrl('/site/download', array('f' => "{$mediaSettings['paths']['files']['path']}/{$model->doc_id}.{$model->file_ext}")) . '">' . AmcWm::t("msgsbase.core", 'Download the file') . '</a>';
}


$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'doc_id',
        array(
            'name' => AmcWm::t("msgsbase.core", "Category Name"),
            'value' => ($contentModel->getParentContent()->category_id) ? $contentModel->getParentContent()->category->getCurrent()->category_name : "",
            'htmlOptions' => array('width' => '230'),
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Name"),
            'value' => $contentModel->title,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "description"),
            'value' => $contentModel->description,
            'type' => 'html',
        ),
        array(
            'name' => 'docFile',
            'type' => 'html',
            'value' => ($model->file_ext) ? $drawDocLink : AmcWm::t("amcBack", "No"),
        ),
    ),
));
?>