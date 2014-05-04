<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
   AmcWm::t("msgsbase.supervisors", "Sections")=> array('/backend/sections/default/index'),
   AmcWm::t("msgsbase.supervisors", "Supervisor")=> array('/backend/sections/default/supervisors'),
   AmcWm::t("msgsbase.supervisors", "Edit"),
);
$this->sectionName = AmcWm::t("msgsbase.core", $contentModel->name);
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("msgsbase.supervisors", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_supervisor', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/sections/supervisors/translate', 'id' => $model->person_id), 'id' => 'translate_person', 'image_id' => 'translate'),
        array('label' =>AmcWm::t("msgsbase.supervisors", 'Back'), 'url' => array('/backend/sections/default/supervisors'), 'id' => 'supervisor_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>