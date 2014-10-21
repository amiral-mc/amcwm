<?php

$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/default/index'),
    $model->company->getCurrent()->company_name,
    AmcWm::t("msgsbase.core", "Company Branches"),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = $contentModel->branch_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/directory/branches/update', 'id' => $model->branch_id, 'cid' => $this->getCompanyId()), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/directory/branches/translate', 'id' => $model->branch_id, 'cid' => $this->getCompanyId()), 'id' => 'translate_branch', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/directory/branches/index', 'cid' => $this->getCompanyId()), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));
$attributes[] = 'branch_id';
$attributes[] = array(
    'label' => AmcWm::t("msgsbase.core", 'Company'),
    'value' => ($model->company_id) ? $model->company->getCurrent()->company_name : "",
);
$attributes[] = 'branch_name';
$attributes = array_merge($attributes, $contentModel->getExtendedAttributeViewValues("branch_address"));
$attributes[] = 'city';
$attributes[] = array(
    'name' => AmcWm::t("msgsbase.core", "Country"),
    'value' => ($model->country) ? Yii::app()->getController()->getCountries(0, $model->country) : "",
);
$attributes = array_merge($attributes, $model->getExtendedAttributeViewValues("email"));
$attributes = array_merge($attributes, $model->getExtendedAttributeViewValues("phone"));
$attributes[] = array(
    'label' => AmcWm::t("msgsbase.core", 'Mobile'),
    'value' => $model->mobile,
);
$attributes = array_merge($attributes, $model->getExtendedAttributeViewValues("fax"));
$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => $attributes
));
?>