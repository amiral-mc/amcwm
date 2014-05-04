<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Sections") => array('/backend/sections/default/index'),
    AmcWm::t("msgsbase.core", "Supervisor") => array('/backend/sections/default/supervisors'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Add Person");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_supervisor', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/sections/default/supervisors'), 'id' => 'supervisor_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>