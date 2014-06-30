<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Ads") => array('/backend/ads/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$formId = Yii::app()->params["adminForm"];
$this->sectionName = "#{$model->parcel_id}";


$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_record', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array("/backend/parcels/default/index"), 'id' => 'admin_list', 'image_id' => 'back'),
    ),
));
$this->renderPartial('_form', array('model' => $model, 'formId' => $formId));
?>