<?php

$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Job requests") => array('/backend/jobs/default/index'),
    AmcWm::t("msgsbase.core", "Jobs"),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = $contentModel->job;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/jobs/jobs/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/jobs/jobs/update', 'id' => $model->job_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/jobs/jobs/translate', 'id' => $model->job_id), 'id' => 'translate_cat', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/jobs/jobs/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'job_id',
        array(
            'name' => AmcWm::t("msgsbase.core", 'Category'),
            'value'=>$model->category->getCurrent()->category_name
        ),
        'job',
        array(
            'name'=>'job_description',
            'type'=>'html'
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", 'Publish Date'),
            'value'=>$model->publish_date
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", 'Expire Date'),
            'value'=>$model->expire_date
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", 'Published'),
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
    ),
));
?>