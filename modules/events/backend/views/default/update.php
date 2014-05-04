<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs=array(
        AmcWm::t("msgsbase.core", "Events") => array('/backend/events/default/index'),  
	AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->getOnlineAttribute('event_header');
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'edit_event', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/events/default/translate', 'id' => $model->event_id), 'id' => 'translate_section', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/events/default/index'), 'id' => 'events_list', 'image_id' => 'back'),        
    ),    
));

?>
<?php echo $this->renderPartial('_form', array('contentModel'=>$contentModel, 'formId'=>$formId,)); ?>