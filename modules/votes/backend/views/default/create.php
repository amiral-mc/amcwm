<?php

$formId = Yii::app()->params["adminForm"];

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Votes Questions") => array('/backend/votes/default/index'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Add question");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_poll', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/votes/default/index'), 'id' => 'polls_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>