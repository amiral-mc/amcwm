<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t($msgsBase, "Articles") => array('/backend/articles/default/index'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t($msgsBase, "Add Article");
//print_r(AmcWm::app()->workflow->module->getFlowFromRoute($this->getRoute()));
//die();
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_article', 'image_id' => 'save'),        
        array('visible'=>AmcWm::app()->hasComponent("workflow"), 'label' => AmcWm::t("amcTools", 'Save & finish'), 'action'=>"save", 'js' => array('formId' => $formId, "params"=>array('save_finish' => 1)), 'id' => 'add_article', 'image_id' => 'save_finish'),        
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/default/index'), 'id' => 'articles_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<?php
$view = $this->getModule()->appModule->getVirtualView("_form");
echo $this->renderPartial($view, array('contentModel' => $contentModel, 'formId' => $formId, 'msgsBase' => $msgsBase));
?>
