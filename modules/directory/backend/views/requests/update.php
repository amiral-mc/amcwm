<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/default/index'),
    AmcWm::t("msgsbase.core", "Requests") => array('/backend/directory/requests/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->company_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'edit_company', 'image_id'=>'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/directory/requests/index'), 'id' => 'companies_list', 'image_id' => 'back'),
    ),    
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId'=>$formId)); ?>