<?php
$this->breadcrumbs = array(    
    AmcWm::t("msgsbase.core", "Multimeda sms news"), 
);
$this->sectionName = AmcWm::t("msgsbase.core", "Manage Videos");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Create'), 'url' => array('/backend/sms/default/create'), 'id' => 'add_video', 'image_id' => 'add'),
        array('label' => AmcWm::t("msgsbase.core", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'edit_video', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.core", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'delete_videos', 'image_id' => 'delete'),
        //array('label' => Yii::t("BackendModule.multimedia", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'publish_videos', 'image_id' => 'publish'),
        //array('label' => Yii::t("BackendModule.multimedia", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'unpublish_videos', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("msgsbase.core", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'videos_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<?php ;?>
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
                'name' => 'video_id',
                'htmlOptions' => array('width' => '16'),
            ),
                     
            array(
                'name' => 'video_header',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'header'=>AmcWm::t("msgsbase.core", 'Video File'),
                'type' => 'html',
                'value'=> 'Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/" . Yii::app()->params["multimedia"]["smsVideos"]["path"] . "/" . $data->getVideoName()',
            ),
            array(
                'name' => 'creation_date',
                'htmlOptions' => array('width' => '100'),
                'value'=>'Yii::app()->dateFormatter->format("dd/MM/y",$data->creation_date)',
            ),            
            array(
                'name' => 'content_lang',
                'value' => '($data->content_lang) ? Yii::app()->params["languages"][$data->content_lang] : ""',
                'htmlOptions' => array('width' => '50'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align'=>'center'),
            ),            
        ),
    ));

    $this->endWidget();
    ?>

</div>
