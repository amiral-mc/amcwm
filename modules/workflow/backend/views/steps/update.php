<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Workflow Steps") => array('/backend/workflow/steps/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $model->flow_title;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId, 'params' => array('mid' => $this->module->module_id)), 'id' => 'edit_flow', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/workflow/steps/index', 'mid' => $this->module->module_id), 'id' => 'polls_list', 'image_id' => 'back'),
    ),
));

echo $this->renderPartial('_form', array('model' => $model, 'formId' => $formId,));
?>