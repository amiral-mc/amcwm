<?php

$model = $contentModel->getParentContent();
$module = $this->module->appModule->currentVirtual;
$options = $this->module->appModule->options;
$this->breadcrumbs = array(
    AmcWm::t($msgsBase, "Articles") => array('/backend/articles/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$approvalTitle = null;
$approvalRoute = null;
$undoApprovalTitle = null;
$approval = false;
if (AmcWm::app()->hasComponent("workflow")) {
    if (AmcWm::app()->workflow->module->hasUserSteps()) {
        $currentFlow = AmcWm::app()->workflow->module->getFlowFromTaskItem($model->article_id);
        if (isset($currentFlow['step_title']) && $currentFlow['step_title']!= "ManageContent") {
            $approvalTitle = AmcWm::t("msgsbase.core", strtoupper("_{$currentFlow['step_title']}_"));
            $undoApprovalTitle = AmcWm::t("msgsbase.core", strtoupper("_REJECT_{$currentFlow['step_title']}_"));
            $approvalRoute  = $currentFlow['route'];
            $approval = true;
        }
    }
}

$this->sectionName = $contentModel->article_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/articles/default/create'), 'id' => 'add_news', 'image_id' => 'add'),
        array('visible' => $approval && $this->getManager()->allowApproved(), 'label' => $approvalTitle, 'url' => array("/{$approvalRoute}", 'id' => $model->article_id), 'id' => 'article_approved', 'image_id' => 'approval'),
        array('visible' => $approval && $this->getManager()->allowApproved(), 'label' => $undoApprovalTitle, 'url' => array("/{$approvalRoute}", 'id' => $model->article_id, 'undo'=>1), 'id' => 'article_approved', 'image_id' => 'undo'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/articles/default/update', 'id' => $model->article_id), 'id' => 'edit_article', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'url' => array('/backend/articles/default/comments', 'item' => $model->article_id), 'id' => 'news_comments', 'image_id' => 'comments'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/articles/default/translate', 'id' => $model->article_id), 'id' => 'translate_article', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
));
?>
<?php

$imagesInfo = $this->getModule()->appModule->mediaPaths;
$drawSliderImage = NULL;
if ($model->article_id && $model->in_slider) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['slider']['path'] . "/" . $model->article_id . "." . $model->in_slider))) {
        $drawSliderImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['slider']['path'] . "/" . $model->article_id . "." . $model->in_slider . "?" . time(), "", array("class" => "image")) . '</div>';
    }
}

$drawImage = NULL;
if ($model->article_id && $model->thumb) {
    if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imagesInfo['list']['path'] . "/" . $model->article_id . "." . $model->thumb))) {
        $drawImage = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $imagesInfo['list']['path'] . "/" . $model->article_id . "." . $model->thumb . "?" . time(), "", array("class" => "image")) . '</div>';
    }
}
$titles = null;
foreach ($contentModel->titles as $title) {
    $titles .= $title->title . "<br />";
}
$infocusName = $this->getInfocucName($model->infocusId);

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'article_id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Parent Article"),
            'value' => ($model->parent_article ? $model->parentArticle->getCurrent()->article_header : '...'),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Article Primary Header"),
            'value' => $contentModel->article_pri_header,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Article Header"),
            'value' => $contentModel->article_header,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Titles"),
            'type' => 'html',
            'value' => $titles,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Article Detail"),
            'value' => $contentModel->article_detail,
            'type' => 'html',
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Tags"),
            'value' => nl2br($contentModel->tags),
            'type' => 'html',
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Section"),
            'value' => Sections::drawSectionPath($model->section_id),
        ),
        array(
            'name' => 'country_code',
            'value' => ($model->country_code) ? $model->countryCode->getCountryName() : NULL,
        ),
        array(
            'name' => 'writer_id',
            'value' => ($model->writer_id && $model->writer->person->getTranslated($contentModel->content_lang)) ? $model->writer->person->getTranslated($contentModel->content_lang)->name : "",
        ),
        array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'archive',
            'value' => ($model->archive) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Content Lang"),
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'In Focus File'),
            'value' => ($infocusName) ? $infocusName : Yii::t('zii', 'Not set'),
            'visible' => $options['default']['check']['addToInfocus'],
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'In Ticker'),
            'value' => ($model->in_ticker) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
//        array(
//            'label' => AmcWm::t("msgsbase.core", 'In Spot'),
//            'value' => ($model->in_spot) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
//        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'In Slider'),
            'type' => 'html',
            'value' => ($model->in_slider) ? AmcWm::t("amcBack", "Yes") . $drawSliderImage : AmcWm::t("amcBack", "No"),
            'visible' => $options['default']['check']['addToSlider'],
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'In List'),
            'type' => 'html',
            'value' => (($model->in_list) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No")),
        ),
        array(
            'name' => 'thumb',
            'type' => 'html',
            'value' => ($model->thumb) ? $drawImage : AmcWm::t("amcBack", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Image Description"),
            'value' => $contentModel->image_description,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'value' => Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->create_date),
        ),
        array(
            'name' => 'publish_date',
            'value' => Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->publish_date),
        ),
        array(
            'name' => 'expire_date',
            'value' => ($model->expire_date) ? Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->expire_date) : NULL,
        ),
    ),
));
?>
