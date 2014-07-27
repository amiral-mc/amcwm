<?php
$loggerUrl = array('/backend/logger/default/index');
if($this->logTable){
    $loggerUrl['from'] = $this->logTable;
}
if($this->itemId){
    $loggerUrl['itemId'] = $this->itemId;
}
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Logger")=>$loggerUrl,
    AmcWm::t("msgsbase.core", "Details"),
);
$this->sectionName = $logInfo['action_name'];
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => $loggerUrl, 'id' => 'logs_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
echo $this->renderPartial($view, array('logDetails' =>$logData, 'logInfo'=>$logInfo));