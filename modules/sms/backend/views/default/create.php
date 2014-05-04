<?php

$formId = Yii::app()->params["adminForm"];

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Multimeda sms news")  => array('/backend/sms/default/index'),    
    AmcWm::t("msgsbase.core", "Create"),
);

$this->sectionName = AmcWm::t("msgsbase.core", "Create Video");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_video', 'image_id' => 'save'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/sms/default/index',), 'id' => 'videos_list', 'image_id' => 'back'),
    ),
));
?>


<?php echo $this->renderPartial('_form', array('model' => $model, 'formId' => $formId)); ?>