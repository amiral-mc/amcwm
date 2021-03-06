<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Documents") => array('/backend/documents/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->title;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'edit_person', 'image_id'=>'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/documents/default/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),    
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId'=>$formId)); ?>