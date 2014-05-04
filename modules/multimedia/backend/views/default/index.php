<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    AmcWm::t("msgsbase.core", "Manage"),
);

$this->sectionName = AmcWm::t("msgsbase.core", "Manage");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/multimedia/default/create'), 'id' => 'add_gallery', 'image_id' => 'add'),
        array('label' =>AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_gallery', 'image_id' => 'edit'),
        array('label' =>AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_galleries', 'image_id' => 'delete'),
        array('label' =>AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_galleries', 'image_id' => 'publish'),
        array('label' =>AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_galleries', 'image_id' => 'unpublish'),
        array('label' =>AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'translate_gallery', 'image_id' => 'translate'),
        array('label' =>AmcWm::t("msgsbase.core", 'Videos'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'videos', 'refId' => 'gid'), 'id' => 'manage_videos', 'image_id' => 'videos'),
        array('label' =>AmcWm::t("msgsbase.core", 'Images'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'images', 'refId' => 'gid'), 'id' => 'manage_images', 'image_id' => 'images'),
        array('label' =>AmcWm::t("msgsbase.core", 'Backgrounds'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'backgrounds', 'refId' => 'gid'), 'id' => 'manage_backgrounds', 'image_id' => 'backgrounds'),
        array('label' =>AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'galleries_search', 'image_id' => 'search'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'galleries_list', 'image_id' => 'back'),
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
        'columns' =>
        array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'gallery_id',
                'htmlOptions' => array('width' => '16'),
            ),
            array(
                'name' => 'gallery_header',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => 'section_name',
                'htmlOptions' => array('width' => '230'),
            ),
            'tags',
            array(
                'name' => 'published',
                'value' => '($data->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20'),
            ),
        ),
    ));

    $this->endWidget();
    ?>
</div>


