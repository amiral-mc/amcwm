<?php

$this->breadcrumbs = array(
    AmcWm::t("msgsbase.channels", "Channels") => array('/backend/maillist/channels/index'),
    AmcWm::t("amcTools", "View"),
);

$this->sectionName = $model->channel;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/maillist/channels/create'), 'id' => 'add_maillist', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/maillist/channels/update', 'id' => $model->id), 'id' => 'edit_maillist', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/maillist/channels/index'), 'id' => 'maillist_list', 'image_id' => 'back'),
    ),
));
$langs = array('ar' => AmcWm::t("msgsbase.channels", "Arabic"), 'en' => AmcWm::t("msgsbase.channels", "English"));
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        'channel',
        array(
            'name' => 'content_lang',
            'value' => isset($langs[$model->content_lang]) ? $langs[$model->content_lang] : NULL,
        ),
    ),
));
