<?php

$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Products") => array('/backend/products/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$this->sectionName = $contentModel->product_name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_article', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/products/default/translate', 'id' => $model->product_id), 'id' => 'translate_product', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/products/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));

echo $this->renderPartial("_form", array('contentModel' => $contentModel, 'formId' => $formId, 'msgsBase' => "msgsbase.core",)
);
