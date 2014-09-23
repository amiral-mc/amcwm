<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticlesReports
 *
 * @author abdallah
 */
class ReportsWidget extends Widget {

    /**
     * @var type boolean render reporters reports
     */
    public $reporters = false;

    public function renderLinks() {
        $moduleId = AmcWm::app()->request->getParam('module');
        $virtualModule = AmcWm::app()->controller->getModule()->appModule->currentVirtual;
        if ($this->reporters && $virtualModule != 'articles') {
            if($this->reporters && $virtualModule == 'news') {
                echo "<div class='row'>";
                echo CHtml::link(AmcWm::t("amcBack", "Search Reporters Reports"), Yii::app()->controller->createUrl('reports', array('rep' => 'reporters', 'module' => $moduleId)), array('class' => 'render-reports-form', "target" => 'reports_dialog_iframe'));
                echo "</div>";
                echo "<div class='row'>";
                echo CHtml::link(AmcWm::t("amcBack", "Search Reporter Reports"), Yii::app()->controller->createUrl('reports', array('rep' => 'reporter', 'module' => $moduleId)), array('class' => 'render-reports-form', "target" => 'reports_dialog_iframe'));
                echo "</div>";
            } else {
                echo "<div class='row'>";
                echo CHtml::link(AmcWm::t("amcBack", "Search Writers Reports"), Yii::app()->controller->createUrl('reports', array('rep' => 'reporters', 'module' => $moduleId)), array('class' => 'render-reports-form', "target" => 'reports_dialog_iframe'));
                echo "</div>";
                echo "<div class='row'>";
                echo CHtml::link(AmcWm::t("amcBack", "Search Writer Reports"), Yii::app()->controller->createUrl('reports', array('rep' => 'reporter', 'module' => $moduleId)), array('class' => 'render-reports-form', "target" => 'reports_dialog_iframe'));
                echo "</div>";
            }
        }
        echo "<div class='row'>";
        echo CHtml::link(AmcWm::t("amcBack", "Search Deskman Reports"), Yii::app()->controller->createUrl('reports', array('rep' => 'deskman', 'module' => $moduleId)), array('class' => 'render-reports-form', "target" => 'reports_dialog_iframe'));
        echo "</div>";
        echo "<div class='row'>";
        echo CHtml::link(AmcWm::t("amcBack", "Search Deskmen Reports"), Yii::app()->controller->createUrl('reports', array('rep' => 'deskmen', 'module' => $moduleId)), array('class' => 'render-reports-form', "target" => 'reports_dialog_iframe'));
        echo "</div>";
        Yii::app()->clientScript->registerScript('popupReportsView', "
            $('.render-reports-form').click(
                function(event){
                   $('#reports_dialog').dialog('open');
                }
            );
        ");
    }

    public function run() {
        echo $this->renderLinks();
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => "reports_dialog",
            'options' => array(
                'title' => AmcWm::t("amcBack", "Report Details"),
                'width' => 800,
                'height' => 600,
                'resizable' => false,
                'autoResize' => false,
                'autoOpen' => false,
                'iframe' => true,
                'modal' => true,
            ),
        ));
        echo '<iframe class="filemanager-iframe" name="reports_dialog_iframe" style="width:765px; height:535px;" id="reports_dialog_iframe" scrolling="auto"></iframe>';
        $this->endWidget('zii.widgets.jui.CJuiDialog');
    }

}
