<?php

$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Jobs") => array('/backend/jobs/jobs/index'),
    AmcWm::t("msgsbase.core", "Jobs Categories"),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = $contentModel->category_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/jobs/categories/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/jobs/categories/update', 'id' => $model->category_id), 'id' => 'edit_jobs', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/jobs/categories/translate', 'id' => $model->category_id), 'id' => 'translate_cat', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/jobs/categories/index'), 'id' => 'jobss_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'category_id',
        'category_name',
        array(
            'name' => AmcWm::t("msgsbase.core", 'Published'),
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
    ),
));
?>