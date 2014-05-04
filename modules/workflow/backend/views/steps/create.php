<?php

$formId = Yii::app()->params["adminForm"];

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Workflow Steps") => array('/backend/workflow/steps/index'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Create workflow");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId, 'params' => array('mid' => $this->module->module_id)), 'id' => 'add_flow', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/workflow/steps/index', 'mid' => $this->module->module_id), 'id' => 'polls_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('model' => $model, 'formId' => $formId)); ?>