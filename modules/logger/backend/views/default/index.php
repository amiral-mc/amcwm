<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Logger"),
);
$params = array();
if($this->logTable){
    $params['from'] = $this->logTable;
}
if($this->itemId){
    $params['itemId'] = $this->itemId;
}
$this->sectionName = AmcWm::t("msgsbase.core", "View system logs");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Details'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params'=>$params), 'id' => 'view_article', 'image_id' => 'show', 'action'=>'view'),
        //array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'log_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'visible'=> !$this->logTable,'url' => array('/backend/default/index'), 'id' => 'articles_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'data-grid',
    'dataProvider' => $logData,
    'selectableRows' => Yii::app()->params["pageSize"],
    'columns' => array(
        array(
            'class' => 'CheckBoxColumn',
            'checked' => '0',
            'checkBoxHtmlOptions' => array("name" => "ids"),
            'htmlOptions' => array('width' => '16', 'align' => 'center'),
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'Action'),
            'value' => '$data["action"]',
            'htmlOptions' => array('style' => 'width:50px; text-align:center')
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'Username'),
            'value' => '$data["username"]',
            'htmlOptions' => array('style' => 'width:50px; text-align:center')
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'IP'),
            'value' => '$data["fromip"]',
            'htmlOptions' => array('style' => 'width:50px; text-align:center')
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'Date'),
            'value' => '$data["action_date"]',
            'htmlOptions' => array('style' => 'width:50px; text-align:center')
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'Refere Title'),
            'value' => '$data["title"]',
            'htmlOptions' => array('style' => 'width:200px;')
        ),     
    )
));