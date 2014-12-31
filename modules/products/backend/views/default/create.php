<?php

$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t('msgsbase.core', "Products") => array('/backend/products/default/index'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t('msgsbase.core', "Add Product");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_product', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/products/default/index'), 'id' => 'products_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<?php

echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId, 'msgsBase' => 'msgsbase.core'));
?>
