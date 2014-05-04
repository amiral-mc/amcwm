<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Writers") => array('/backend/writers/default/index'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Add Writer");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_writer', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/writers/default/index'), 'id' => 'writers_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>