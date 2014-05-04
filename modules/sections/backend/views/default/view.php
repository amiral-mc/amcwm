<?php
$imageSizesInfo = $this->getModule()->appModule->mediaPaths;
$model = $contentModel->getParentContent();
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/sections/default/create', 'sid' => $this->getParentId()), 'id' => 'add_section', 'image_id' => 'add');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/sections/default/update', 'sid' => $this->getParentId(), 'id' => $model->section_id), 'id' => 'edit_section', 'image_id' => 'edit');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/sections/default/translate', 'sid' => $this->getParentId(), 'id' => $model->section_id), 'id' => 'translate_section', 'image_id' => 'translate');
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Sub-Sections'), 'url' => array('/backend/sections/default/index', 'sid' => $model->section_id), 'id' => 'sub_sections', 'image_id' => 'sub-sections');
if ($this->getParentId()) {
    $parentSection = $this->getParentSection()->getTranslated($contentModel->content_lang)->section_name;
    $sections = Sections::getTree($model->section_id);
    $this->breadcrumbs[AmcWm::t("msgsbase.core", "Sections")] = array('/backend/sections/default/index');
    foreach ($sections as $section) {
        $this->breadcrumbs[$section['section_name']] = array('/backend/sections/default/view', 'id' => $section['section_id'], 'sid' => $section['parent_section']);
    }
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/sections/default/index', 'sid' => $this->getParentId()), 'id' => 'modules_list', 'image_id' => 'back');
} else {
    $parentSection = NULL;
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/sections/default/index'), 'id' => 'modules_list', 'image_id' => 'back');
    $this->breadcrumbs[] = AmcWm::t("msgsbase.core", "Sections");
}
$this->breadcrumbs[] = AmcWm::t("amcTools", "View");
$this->sectionName = $contentModel->section_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
<?php


$drawImage = NULL;
if ($model->section_id && $model->image_ext) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageSizesInfo['topContent']['path'] . "/" . $model->section_id . "." . $model->image_ext))) {
        $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imageSizesInfo['topContent']['path'] . "/" . $model->section_id . "." . $model->image_ext . "?" . time(), "", array("class" => "image", "style" => "max-width:250px;")) . '</div>';
    }
}

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'section_id',
        array(
            'label' => AmcWm::t("msgsbase.core", 'Section Name'),
            'value' => Sections::drawSectionPath($model->section_id),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Description'),
            'value' => $contentModel->description,
            'type' => 'html',
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Tags'),
            'value' => $contentModel->tags,
        ), 
        array(
            'label' => AmcWm::t("msgsbase.core", 'Supervisor'),
            'value' => ($contentModel->supervisor && $contentModel->supervisorPerson->getTranslated($contentModel->content_lang)) ? $contentModel->supervisorPerson->getTranslated($contentModel->content_lang)->name : "",
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Published'),
            'value' => ($model->published) ? AmcWm::t("amcFront", "Yes") : AmcWm::t("amcFront", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
        ),
        array(
            'name' => 'imageFile',
            'type' => 'html',
            'value' => ($model->image_ext) ? $drawImage : AmcWm::t("amcBack", "No"),
        ),
    ),
));
?>
