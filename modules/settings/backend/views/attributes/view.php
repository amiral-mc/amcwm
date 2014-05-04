<?php

$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Configuration") => array('/backend/settings/default/index'),
    AmcWm::t("msgsbase.core", "Attributes") => array('/backend/settings/attributes/index'),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = $contentModel->label;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/settings/attributes/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/settings/attributes/update', 'id' => $model->attribute_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/settings/attributes/translate', 'id' => $model->attribute_id), 'id' => 'translate_cat', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/settings/attributes/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'attribute_id',
        'label',
            array(
            'name' => 'is_new_type',
            'label' => AmcWm::t("msgsbase.core", 'New Type'),
            'value' => ($model->is_new_type) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Module'),
            'value' => $model->getModuleName(),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Attribute Type'),
            'value' => $model->getAttributeType(),
        ),
    ),
));
?>