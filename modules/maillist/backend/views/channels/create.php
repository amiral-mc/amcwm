<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
   AmcWm::t("msgsbase.channels", "Channels") => array('/backend/maillist/channels/index'),
   AmcWm::t("msgsbase.core", "Create"),
);

$this->sectionName =AmcWm::t("msgsbase.channels", "Add Channel");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("amcTools", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'add_maillist', 'image_id' => 'save'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/maillist/channels/index'), 'id' => 'maillist_list', 'image_id' => 'back'),
    ),    
));
?>
<?php echo $this->renderPartial('_form', array('model' => $model, 'formId' => $formId)); ?>