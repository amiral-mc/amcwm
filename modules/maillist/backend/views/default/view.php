<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Maillist") => array('/backend/maillist/default/index'),
    AmcWm::t("amcTools", "View"),
);

$this->sectionName = $model->maillistUsers->email;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/maillist/default/create'), 'id' => 'add_maillist', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/maillist/default/update', 'id' => $model->id), 'id' => 'edit_maillist', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/maillist/default/index'), 'id' => 'maillist_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Name"),
            'value' => $model->maillistUsers->name,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Email"),
            'value' => $model->maillistUsers->email,
        ),
        array(
            'name' => 'status',
            'value' => ($model->status) ? AmcWm::t("amcTools", "Yes") : AmcWm::t("amcTools", "No"),
        ),
    ),
));
