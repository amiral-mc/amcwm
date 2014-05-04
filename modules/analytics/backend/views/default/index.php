<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Logger"),
);

$this->sectionName = AmcWm::t("msgsbase.core", "View system logs");

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'data-grid',
    'dataProvider' => $logData,
    'columns' => array(
        array(
            'name' => AmcWm::t('msgsbase.core', 'Action'),
            'value' => 'AmcWm::t("msgsbase.core", $data["action"]);',
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'Username'),
            'value' => '$data["username"]',
            'htmlOptions' => array('style' => 'width:110px; text-align:center')
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'IP'),
            'value' => '$data["fromip"]',
            'htmlOptions' => array('style' => 'width:110px; text-align:center')
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'Date'),
            'value' => '$data["action_date"]',
            'htmlOptions' => array('style' => 'width:110px; text-align:center')
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'Refere Name'),
            'value' => '$data["refere_name"]',
            'htmlOptions' => array('style' => 'width:100px; text-align:center')
        ),
        array(
            'name' => AmcWm::t('msgsbase.core', 'Refere Name'),
            'value' => 'print_r($data["refere_data"])',
            'type'=>'raw',
            'htmlOptions' => array('style' => 'width:100px; text-align:center')
        )
    )
));
?>