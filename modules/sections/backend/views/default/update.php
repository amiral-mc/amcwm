<?php

$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Sections") => array('/backend/sections/default/index'),
);
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_section', 'image_id' => 'save');
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Sub-Sections'), 'url' => array('/backend/sections/default/index', 'sid' => $model->section_id), 'id' => 'sub_sections', 'image_id' => 'sub-sections');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/sections/default/translate', 'sid' => $this->getParentId(), 'id' => $model->section_id), 'id' => 'translate_section', 'image_id' => 'translate');
if ($this->getParentId()) {
    $sections = Sections::getTree($this->getParentId());
    $this->breadcrumbs[AmcWm::t("msgsbase.core", "Sections")] = array('/backend/sections/default/index');
    foreach ($sections as $section) {
        $this->breadcrumbs[$section['section_name']] = array('/backend/sections/default/view', 'id' => $section['section_id'], 'sid' => $section['parent_section']);
    }
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/sections/default/index', 'sid' => $this->getParentId()), 'id' => 'modules_list', 'image_id' => 'back');
} else {
    $toolsItems[] = array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/sections/default/index'), 'id' => 'modules_list', 'image_id' => 'back');
}
$this->breadcrumbs[] = AmcWm::t("msgsbase.core", "Edit");
$this->sectionName = $contentModel->section_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>