<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/default/index'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Add new company");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'add_person', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/directory/default/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>