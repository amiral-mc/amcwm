<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Jobs"),
);

$this->sectionName = AmcWm::t("amcTools", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Jobs'), 'url' => array('/backend/jobs/jobs/index'), 'id' => 'add_category', 'image_id' => 'add', 'visible' => $options['default']['integer']['allowJobs']),
        array('label' => AmcWm::t("msgsbase.core", 'Requests'), 'url' => array('/backend/jobs/requests/index'), 'id' => 'manage_requests', 'image_id' => 'requests'),
        array('label' => AmcWm::t("msgsbase.core", 'Users CVs'), 'url' => array('/backend/jobs/usersCvs/index'), 'id' => 'manage_cvs', 'image_id' => 'requests', 'visible' => $options['default']['integer']['allowUsersApply']),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'glossary_list', 'image_id' => 'back'),
    ),
));
?>