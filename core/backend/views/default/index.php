<?php
$this->pageTitle = Yii::app()->name;
$this->sectionName = AmcWm::t("amcBack", "manage_content");
$this->widget('amcwm.core.widgets.modulesTools.ModulesTools', array(
    'id' => 'tools-grid',
    'items' => Yii::app()->user->getSubModulesList('backend'),
    //'items' => Yii::app()->user->getSubModulesList(Yii::app()->params['backendModuleName']),
));
?> 