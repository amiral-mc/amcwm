<?php

$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Writers") => array('/backend/writers/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_writer', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/writers/default/translate', 'id' => $model->person_id), 'id' => 'translate_person', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/writers/default/index'), 'id' => 'writers_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>