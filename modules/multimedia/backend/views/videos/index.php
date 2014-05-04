<?php
$galleryId = $this->gallery->getParentContent()->gallery_id;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view' , 'id'=> $galleryId),
    AmcWm::t("msgsbase.core", "Videos"), 
);


$this->sectionName = AmcWm::t("msgsbase.core", "Manage Videos");
$tools = array();
$tools[] = array('label' =>AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/multimedia/videos/create' , 'gid'=> $galleryId), 'id' => 'add_video', 'image_id' => 'add');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params'=>array('gid'=>$galleryId)), 'id' => 'edit_video', 'image_id' => 'edit');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params'=>array('gid'=>$galleryId)), 'id' => 'delete_videos', 'image_id' => 'delete');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params'=>array('gid'=>$galleryId)), 'id' => 'publish_videos', 'image_id' => 'publish');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params'=>array('gid'=>$galleryId)), 'id' => 'unpublish_videos', 'image_id' => 'unpublish');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params'=>array('gid'=>$galleryId)), 'id' => 'translate_gallery', 'image_id' => 'translate');
$tools[] = array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'comments' ,'params'=>array('gid'=>$galleryId), 'refId' => 'item'), 'id' => 'manage_videos_comments', 'image_id' => 'comments');
if($this->getModule()->appModule->useDopeSheet){
    $tools[] = array('label' => AmcWm::t("msgsbase.core", 'Dope Sheet'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'dopeSheet' ,'params'=>array('gid'=>$galleryId), 'refId' => 'mmId'), 'id' => 'manage_video_dopesheet', 'image_id' => 'dope_sheet');
}
$tools[] = array('label' =>AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'videos_search', 'image_id' => 'search');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/default/index'), 'id' => 'galleries_list', 'image_id' => 'back');
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $tools, 
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
                'name' => 'creation_date',
                'htmlOptions' => array('width' => '100'),
                'value'=>'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$data->creation_date)',
            ),
            array(
                'name' => 'hits',
                'htmlOptions' => array('width' => '30', 'align'=>'center'),
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
                'htmlOptions' => array('width' => '20', 'align'=>'center'),
            ),
            array(
                'name' => 'in_slider',
                'value' => '($data->in_slider) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align'=>'center'),
            ),
             array(
                'class' => 'CButtonColumn',
                'template' => '{up} {down}',
                'buttons' => array(
                    'up' => array(
                        'label' => 'up',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/up.gif',
                        'url' => 'Html::createUrl("/backend/multimedia/videos/sort", array("id"=>$data->video_id, "direction"=>"up", "gid"=>' . $galleryId . '))',
                    ),
                    'down' => array(
                        'label' => 'down',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/down.gif',
                        'url' => 'Html::createUrl("/backend/multimedia/videos/sort", array("id"=>$data->video_id, "direction"=>"down", "gid"=>' . $galleryId . '))',
                    ),
                ),
                'htmlOptions' => array('width' => '40', 'align' => 'center'),
            ),
        ),
    ));

    $this->endWidget();
    ?>

</div>
