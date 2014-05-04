<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Logger")=>array('/backend/logger/default/index'),
    AmcWm::t("msgsbase.core", "Details"),
);
$this->sectionName = $logInfo['action_name'];
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/logger/default/index'), 'id' => 'logs_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
echo $this->renderPartial($view, array('logDetails' =>$logData, 'logInfo'=>$logInfo));