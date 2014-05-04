<?php

$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t($msgsBase, "Articles") => array('/backend/issues/default/issueArticles', 'issueId' => AmcWm::app()->request->getParam('issueId')),
    AmcWm::t("amcTools", "Edit"),
);
//$this->sectionName = AmcWm::t("msgsbase.core", "Update News");
$this->sectionName = $contentModel->article_header;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_article', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/articles/default/translate', 'id' => $model->article_id, 'issueId' => AmcWm::app()->request->getParam('issueId')), 'id' => 'translate_article', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/issues/default/issueArticles', 'issueId' => AmcWm::app()->request->getParam('issueId')), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<?php

$view = $view = $this->getModule()->appModule->getVirtualView("_form");
echo $this->renderPartial($view, array('contentModel' => $contentModel, 'formId' => $formId, 'msgsBase' => $msgsBase,)
);
?>