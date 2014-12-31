<?php

$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Products") => array('/backend/products/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);

$this->sectionName = $contentModel->product_name;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/products/default/create'), 'id' => 'add_product', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/products/default/update', 'id' => $model->product_id), 'id' => 'edit_product', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'url' => array('/backend/products/default/comments', 'item' => $model->product_id), 'id' => 'product_comments', 'image_id' => 'comments'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/products/default/translate', 'id' => $model->product_id), 'id' => 'translate_product', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/products/default/index'), 'id' => 'products_list', 'image_id' => 'back'),
    ),
));

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'product_id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Product Name"),
            'value' => $contentModel->product_name,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Product Brief"),
            'value' => $contentModel->product_brief,
            'type' => 'html',
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Product Description"),
            'value' => $contentModel->product_description,
            'type' => 'html',
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Product Specification"),
            'value' => $contentModel->product_specifications,
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