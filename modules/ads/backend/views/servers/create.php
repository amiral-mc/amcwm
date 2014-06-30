<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Ads") => array('/backend/ads/default/index'),
    AmcWm::t("msgsbase.servers", 'Ad Servers') => array('/backend//ads/servers/index'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t("amcTools", "New");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_record', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend//ads/servers/index'), 'id' => 'records_list', 'image_id' => 'back'),
    ),
));
?>
<?php $this->renderPartial('_form', array('model' => $model, 'formId' => $formId)); ?>