<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Sections") => array('/backend/sections/default/index'),
);
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_section', 'image_id' => 'save');
if ($this->getParentId()) {
    $parentSection = $this->getParentSection()->getTranslated($contentModel->content_lang)->section_name;
    $sections = Sections::getTree($this->getParentId());
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
$this->breadcrumbs[] = AmcWm::t("amcTools", "Create");
$this->sectionName = AmcWm::t("msgsbase.core", "Add Section");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>