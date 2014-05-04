<?php

$formId = AmcWm::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Configuration") => array('/backend/settings/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Configuration");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_user', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Attributes'), 'url' => array('/backend/settings/attributes/index'), 'id' => 'add_attribute', 'image_id' => 'attributes'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/settings/default/index'), 'id' => 'congiguration_view', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('model' => $model, 'configProperties' => $configProperties, 'formId' => $formId)); ?>