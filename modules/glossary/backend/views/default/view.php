<?php

$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Glossary") => array('/backend/glossary/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$this->sectionName = $model->expression;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/glossary/default/update', 'id' => $model->expression_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/glossary/default/translate', 'id' => $model->expression_id), 'id' => 'translate_exp', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/glossary/default/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'expression_id',
        array(
            'name'=>'category_id',
//            'value'=> $model->category->
        ),
        'expression',
        array(
            'name' => 'meaning',
            'value' => $contentModel->meaning,
        ),
        array(
            'name' => 'description',
            'value' => $contentModel->description,
        ),
    ),
));
?>