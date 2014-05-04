<?php
$model = $contentModel->getParentContent();
$breadcrumbs[AmcWm::t("msgsbase.core", "Member Area")] = array('/users/default/index');
$breadcrumbs[AmcWm::t("msgsbase.core", "_manage_company_")] = array('/directory/members/index');
$breadcrumbs[AmcWm::t("msgsbase.core", 'Branches')] =  array('/directory/branches/index');
$breadcrumbs[] = AmcWm::t("amcTools", "View");
$pageContent = $this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/directory/branches/update', 'id' => $model->branch_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/directory/branches/translate', 'id' => $model->branch_id), 'id' => 'translate_branch', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/directory/branches/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
), true);

$pageContent .= $this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
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
), true);
$this->widget('PageContentWidget', array(
    'id' => 'view_branch',
    'contentData' => $pageContent,
    'title' => AmcWm::t("msgsbase.core", '_manage_company_'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>