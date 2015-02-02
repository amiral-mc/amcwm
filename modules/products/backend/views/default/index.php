<?php
$this->breadcrumbs = array(
    AmcWm::t('msgsbase.core', "Products"),
);
$this->sectionName = AmcWm::t('msgsbase.core', "Manage Products");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Gallery'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'gallery', 'refId' => 'pid'), 'id' => 'manage_gallery', 'image_id' => 'images'),
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/products/default/create'), 'id' => 'add_product', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_product', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Preview'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'view_product', 'image_id' => 'view'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_products', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_products', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_products', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'comments', 'refId' => 'item'), 'id' => 'manage_product_comments', 'image_id' => 'comments'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'translate_product', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'products_comments_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'product_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => Yii::app()->params["adminForm"],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    $dataProvider = $model->search();
    $dataProvider->pagination->pageSize = Yii::app()->params["pageSize"];
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'data-grid',
        'dataProvider' => $dataProvider,
        'selectableRows' => Yii::app()->params["pageSize"],
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'product_id',
                'htmlOptions' => array('width' => '40'),
                'header' => AmcWm::t("msgsbase.core", 'ID'),
            ),
            array(
                'name' => 'create_date',
                'htmlOptions' => array('width' => '100'),
                'value' => 'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$data->getParentContent()->create_date)',
                'header' => AmcWm::t("msgsbase.core", 'Creation Date'),
            ),
            array(
                'name' => 'product_name',
                'value' => '$data->product_name',
                'htmlOptions' => array('width' => '250'),
                'header' => AmcWm::t("msgsbase.core", 'Product Name'),
            ),
            array(
                'name' => 'section_id',
                'value' => '($data->section_name) ? $data->section_name : "--"',
                'htmlOptions' => array('width' => '60', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Section'),
            ),
            array(
                'name' => 'content_lang',
                'value' => '($data->content_lang) ? Yii::app()->params["languages"][$data->content_lang] : ""',
                'htmlOptions' => array('width' => '50', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Content Lang'),
            ),
            array(
                'value' => '$data->getParentContent()->hits',
                'htmlOptions' => array('width' => '60', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Hits'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->getParentContent()->published == ActiveRecord::PUBLISHED) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Published'),
            ),
            array(
                'header' => AmcWm::t("amcTools", 'Sort'),
                'class' => 'CButtonColumn',
                'template' => '{up} {down}',
                'buttons' => array(
                    'up' => array(
                        'label' => 'up',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/up.gif',
                        'url' => 'Html::createUrl("/backend/products/default/sort", array("id" => $data->product_id, "direction" => "up", "module"=>Yii::app()->request->getParam("module")))',
                    ),
                    'down' => array(
                        'label' => 'down',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/down.gif',
                        'url' => 'Html::createUrl("/backend/products/default/sort", array("id" => $data->product_id, "direction" => "down", "module"=>Yii::app()->request->getParam("module")))',
                    ),
                ),
                'htmlOptions' => array('width' => '40', 'align' => 'center', 'class' => 'dataGridLinkCol'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>