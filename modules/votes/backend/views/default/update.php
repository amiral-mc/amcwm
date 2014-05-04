<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs=array(
       AmcWm::t("msgsbase.core", "Votes Questions") => array('/backend/votes/default/index'),  
	AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->ques;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("amcTools", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'edit_poll', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/votes/default/translate', 'id' => $model->ques_id), 'id' => 'translate_person', 'image_id' => 'translate'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/votes/default/index'), 'id' => 'polls_list', 'image_id' => 'back'),
    ),    
));

?>
<?php echo $this->renderPartial('_form', array('contentModel'=>$contentModel, 'formId'=>$formId,)); ?>