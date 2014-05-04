<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Job requests") => array('/backend/jobs/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$this->sectionName = $model->name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/jobs/default/update', 'id' => $model->request_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/jobs/default/translate', 'id' => $model->request_id), 'id' => 'translate_exp', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/jobs/default/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'expression_id',
        array(
            'name' => 'request_id',
//            'value'=> $model->category->
        ),
        'expression',
        array(
            'name' => 'name',
            'value' => $model->name,
        ),
        array(
            'name' => 'sex',
            'value' => $model->sex,
        ),
        array(
            'name' => 'email',
            'value' => $model->email,
        ),
        array(
            'name' => 'marital',
            'value' => $model->marital,
        ),
        array(
            'name' => 'driving_license',
            'value' => $model->driving_license,
        ),
        array(
            'name' => 'phone',
            'value' => $model->phone,
        ),
    ),
));
?>