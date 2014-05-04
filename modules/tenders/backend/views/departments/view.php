<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Tenders") => array('/backend/tenders/default/index'),
    AmcWm::t("msgsbase.core", "Departments"),
    AmcWm::t("amcTools", "View"),
);

$this->sectionName = $contentModel->department_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/tenders/departments/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/tenders/departments/update', 'id' => $model->department_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/tenders/departments/translate', 'id' => $model->department_id), 'id' => 'translate_category', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/tenders/departments/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'department_id',
        'department_name',
        array(
            'name' => AmcWm::t("msgsbase.core", "Published"),
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
    ),
));
?>