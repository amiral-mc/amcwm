<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Workflow Steps") => array('/backend/workflow/steps/index', 'mid' => $this->module->module_id),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = $model->flow_title;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/workflow/steps/create', 'mid' => $this->module->module_id), 'id' => 'add_flow', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/workflow/steps/update', 'id' => $model->flow_id, 'mid' => $this->module->module_id), 'id' => 'edit_flow', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/workflow/steps/index', 'mid' => $this->module->module_id), 'id' => 'polls_list', 'image_id' => 'back'),
    ),
));


$steps = "";
foreach ($model->workflowSteps as $step) {
    $steps .= $step->step_title . "<br />";
}


$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'ques_id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Workflow"),
            'value' => $model->flow_title,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Workflow Steps"),
            'value' => $steps,
            'type' => 'html',
        ),
        array(
            'name' => 'enabled',
            'value' => ($model->enabled) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
    ),
));
?>
