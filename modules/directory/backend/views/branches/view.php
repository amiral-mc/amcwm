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

$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'branch_id',
        array(
            'name' => AmcWm::t("msgsbase.core", "Company"),
            'value' => ($model->company_id) ?$model->company->getCurrent()->company_name:"",
        ),
        
        'branch_name',
        'branch_address',
        'city',
        array(
            'name' => AmcWm::t("msgsbase.core", "Country"),
            'value' => ($model->country)?Yii::app()->getController()->getCountries(0, $model->country):"",
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Email"),
            'value' => $model->email,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Phone"),
            'value' => $model->phone,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Fax"),
            'value' => $model->fax,
        ),
    ),
));
?>