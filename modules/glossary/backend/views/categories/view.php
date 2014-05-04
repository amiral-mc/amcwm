<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Glossary") => array('/backend/glossary/default/index'),
    AmcWm::t("msgsbase.core", "Glossary Categories"),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = $contentModel->category_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/glossary/categories/update', 'id' => $model->category_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/glossary/categories/translate', 'id' => $model->category_id), 'id' => 'translate_cat', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/glossary/categories/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'category_id',
        'category_name',
        array(
            'label' => AmcWm::t("msgsbase.core", "Published"),
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
    ),
));
?>