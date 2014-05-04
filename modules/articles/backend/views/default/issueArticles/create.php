<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t($msgsBase, "Articles") => array('/backend/issues/default/issueArticles', 'issueId' => AmcWm::app()->request->getParam('issueId')),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t($msgsBase, "Add Article");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_article', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/issues/default/issueArticles', 'issueId' => AmcWm::app()->request->getParam('issueId')), 'id' => 'articles_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<?php

$view = $this->getModule()->appModule->getVirtualView("_form");
echo $this->renderPartial($view, array('contentModel' => $contentModel, 'formId' => $formId, 'msgsBase' => $msgsBase)
);
?>
