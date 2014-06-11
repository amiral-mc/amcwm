<?php
$model = $contentModel->getParentContent();
$module = $this->module->appModule->currentVirtual;
$options = $this->module->appModule->options;
$this->breadcrumbs = array(
    AmcWm::t($msgsBase, "Articles") => array('/backend/articles/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);

$this->sectionName = $contentModel->article_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/articles/default/create'), 'id' => 'add_news', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/articles/default/update', 'id' => $model->article_id), 'id' => 'edit_article', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/articles/default/translate', 'id' => $model->article_id), 'id' => 'translate_article', 'image_id' => 'translate'),
        array('label' => AmcWm::t("msgsbase.breaking", 'Details'), 'url' => array('/backend/articles/default/more', 'id' => $model->article_id), 'id' => 'news_comments', 'image_id' => 'articles'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
));
?>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'article_id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Article Header"),
            'value' => $contentModel->article_header,
        ),
        array(
            'label' => AmcWm::t("msgsbase.news", "Source"),
            'value' => ($model->news->source) ? $model->news->source->getCurrent()->source : null,
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
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Content Lang"),
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
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
