<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/default/index'),
    $contentModel->getParentContent()->company->getCurrent()->company_name,
    AmcWm::t("msgsbase.core", "Company Branches"),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->branch_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId, 'params' => array('cid' => $this->getCompanyId())), 'id' => 'edit_category', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/directory/branches/index', 'cid' => $this->getCompanyId()), 'id' => 'categories_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>