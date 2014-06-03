<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.news", "Articles")=>array('/backend/articles/default/index'),
    AmcWm::t("msgsbase.sources", "Sources") => array('/backend/articles/sources/index'),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = $contentModel->source;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/articles/sources/create'), 'id' => 'add_source', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/articles/sources/update', 'id'=>$model->source_id), 'id' => 'edit_source', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/articles/sources/translate', 'id'=>$model->source_id), 'id' => 'translate_source', 'image_id' => 'translate'),        
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/sources/index'), 'id' => 'sources_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'source_id',
        array(
            'label' => AmcWm::t("msgsbase.sources", 'Source'),
            'value' => $contentModel->source,
        ),
        array(
            'label' => AmcWm::t("msgsbase.sources", 'URL'),
            'value' => $model->url,
        ),
    ),
));
?>
