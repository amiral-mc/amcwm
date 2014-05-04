<?php
$imagePath = Yii::app()->baseUrl . "/" . Yii::app()->getController()->imageInfo['path'];
$imagePath = str_replace("{gallery_id}", $this->gallery->gallery_id, $imagePath);
//die(AmcWm::app()->appModule->getAppModulePathAlias().".messages.core");
//die(AmcWm::app()->appModule->getId());
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $this->gallery->gallery_id),
    AmcWm::t("msgsbase.core", "_{$this->getId()}_title_"),
);

$this->sectionName = AmcWm::t("msgsbase.core", "_manage_{$this->getId()}_");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/multimedia/' . $this->getId() . '/create', 'gid' => $this->gallery->gallery_id), 'id' => 'add_image', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('gid' => $this->gallery->gallery_id)), 'id' => 'edit_image', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('gid' => $this->gallery->gallery_id)), 'id' => 'delete_images', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('gid' => $this->gallery->gallery_id)), 'id' => 'publish_images', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('gid' => $this->gallery->gallery_id)), 'id' => 'unpublish_images', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('gid' => $this->gallery->gallery_id)), 'id' => 'translate_gallery', 'image_id' => 'translate'),
        array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'comments', 'params' => array('gid' => $this->gallery->gallery_id), 'refId' => 'item'), 'id' => 'manage_images_comments', 'image_id' => 'comments'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'images_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/default/index'), 'id' => 'galleries_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<div class="search-form" style="display:none">
    <?php
    $this->renderPartial(AmcWm::app()->appModule->getViewPathAlias() . '.images._search', array(
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
                'name' => 'image_id',
                'htmlOptions' => array('width' => '16'),
            ),
            array(
                'value' => 'CHtml::image(str_replace("{gallery_id}", $data->gallery_id,Yii::app()->baseUrl . "/" . Yii::app()->getController()->imageInfo["path"] . "/{$data->image_id}-th.{$data->ext}"))',  
                'type' => 'html',
                'htmlOptions' => array('width' => '20'),
            ),
            array(
                'name' => 'image_header',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => 'creation_date',
                'htmlOptions' => array('width' => '100'),
                'value' => 'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$data->creation_date)',
            ),
            array(
                'name' => 'hits',
                'htmlOptions' => array('width' => '30', 'align' => 'center'),
            ),
            array(
                'name' => 'username',
                'htmlOptions' => array('width' => '30'),
                'value' => '$data->username',
            ),
            array(
                'name' => 'published',
                'value' => '($data->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
            array(
                'name' => 'in_slider',
                'value' => '($data->in_slider) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
            array(
                'class' => 'CButtonColumn',
                'template' => '{up} {down}',
                'buttons' => array(
                    'up' => array(
                        'label' => 'up',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/up.gif',
                        'url' => 'Html::createUrl("/backend/multimedia/'.$this->getId().'/sort", array("id"=>$data->image_id, "direction"=>"up", "gid"=>$data->gallery_id))',
                    ),
                    'down' => array(
                        'label' => 'down',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/down.gif',
                        'url' => 'Html::createUrl("/backend/multimedia/'.$this->getId().'/sort", array("id"=>$data->image_id, "direction"=>"down", "gid"=>$data->gallery_id))',
                    ),
                ),
                'htmlOptions' => array('width' => '40', 'align' => 'center'),
            ),
        ),
    ));

    $this->endWidget();
    ?>

</div>