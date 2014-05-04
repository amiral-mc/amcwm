<?php

$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Member Area") => array('/users/default/index'),
    AmcWm::t($msgsBase, "Directory") => array('/directory/members/index'),
    AmcWm::t($msgsBase, "Articles") => array('/articles/manage/index'),
    AmcWm::t("amcTools", "Edit"),
);
$pageContent = $this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_article', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/articles/manage/translate', 'id' => $model->article_id), 'id' => 'translate_article', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/articles/manage/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
        ), true);
?>

<?php

$view = $this->getModule()->appModule->getVirtualView("_form");
$pageContent .= $this->renderPartial($view, array('contentModel' => $contentModel, 'formId' => $formId, 'msgsBase' => $msgsBase), true);
$this->widget('PageContentWidget', array(
    'id' => 'translae_company',
    'contentData' => $pageContent,
    'title' => AmcWm::t($msgsBase, '_manage_company_'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>