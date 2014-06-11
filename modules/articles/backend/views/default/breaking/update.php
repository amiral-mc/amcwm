<?php

$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t($msgsBase, "Articles") => array('/backend/articles/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
//$this->sectionName = AmcWm::t("msgsbase.core", "Update News");
$this->sectionName = $contentModel->article_header;
$savefinish = false;
if (AmcWm::app()->hasComponent("workflow")) {
    if (AmcWm::app()->workflow->module->hasUserSteps()) {        
        $currentFlow = AmcWm::app()->workflow->module->getFlowFromRoute($this->getRoute());
        if (isset($currentFlow['step_title']['ManageContent'])) {
            $savefinish = true;
        }
    }
}
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_article', 'image_id' => 'save'),
        array('visible' => $savefinish, 'label' => AmcWm::t("amcTools", 'Save & finish'), 'action' => "save", 'js' => array('formId' => $formId, "params" => array('save_finish' => 1)), 'id' => 'add_article', 'image_id' => 'save_finish'),
        array('label' => AmcWm::t("msgsbase.breaking", 'Details'), 'url' => array('/backend/articles/default/more', 'id' => $model->article_id), 'id' => 'news_comments', 'image_id' => 'articles'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/articles/default/translate', 'id' => $model->article_id), 'id' => 'translate_article', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<?php

$view = $view = $this->getModule()->appModule->getVirtualView("_form");
echo $this->renderPartial($view, array('contentModel' => $contentModel, 'formId' => $formId, 'msgsBase' => $msgsBase,)
);
?>