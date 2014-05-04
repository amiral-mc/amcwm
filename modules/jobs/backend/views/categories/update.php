<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Jobs") => array('/backend/jobs/jobs/index'),
    AmcWm::t("msgsbase.core", "Jobs Categories"),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->category_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_category', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/jobs/categories/index'), 'id' => 'categories_list', 'image_id' => 'back'),
    ),
));

echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); 
?>