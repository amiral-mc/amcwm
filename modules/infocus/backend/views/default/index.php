<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Infocus"),
);

$this->sectionName = AmcWm::t("msgsbase.core", AmcWm::t("msgsbase.core", "Manage"));

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Create'), 'url' => array('/backend/infocus/default/create'), 'id' => 'add_news', 'image_id' => 'add'),
        array('label' => AmcWm::t("msgsbase.core", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_news', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.core", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_news', 'image_id' => 'delete'),
        array('label' => AmcWm::t("msgsbase.core", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_news', 'image_id' => 'publish'),
        array('label' => AmcWm::t("msgsbase.core", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_news', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("msgsbase.core", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'news_comments_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'news_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
        'contentModel' => $contentModel,
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

    $dataProvider = $contentModel->search();
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
                'name' => 'infocus_id',
                'htmlOptions' => array('width' => '40', 'align' => 'center'),
            ),
            array(
                'name' => 'create_date',
                'htmlOptions' => array('width' => '100'),
                'value' => 'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$data->getParentContent()->create_date)',
                'header' => AmcWm::t("msgsbase.core", 'Creation Date'),
            ),            
            array(
                'name' => 'header',
                'value' => '$data->header',
                'htmlOptions' => array('width' => '250'),
                'header' => AmcWm::t("msgsbase.core", 'Header'),
            ),
            array(
                'name' => 'section_id',
                'value' => '($data->getParentContent()->section_id)?$data->getParentContent()->section->getCurrent()->section_name:""',
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
                'name' => 'published',
                'value' => '($data->getParentContent()->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Published'),
            ),
//            array(
//                'class' => 'CButtonColumn',
//                'template' => '{up} {down}',
//                'buttons' => array(
//                    'up' => array(
//                        'label' => 'up',
//                        'imageUrl' => Yii::app()->request->baseUrl . '/images/up.gif',
//                        'url' => 'Html::createUrl("/backend/infocus/default/sort", array("id" => $data_id, "direction" => "up",))',
//                    ),
//                    'down' => array(
//                        'label' => 'down',
//                        'imageUrl' => Yii::app()->request->baseUrl . '/images/down.gif',
//                        'url' => 'Html::createUrl("/backend/infocus/default/sort", array("id" => $data_id, "direction" => "down",))',
//                    ),
//                ),
//                'htmlOptions' => array('width' => '40', 'align' => 'center', 'class'=>'dataGridLinkCol'),
//            ),
        )
    ));
    $this->endWidget();
    ?>
</div>

<?php /* $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'news-grid',
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'columns'=>array(
  'article_id',
  'article_sort',
  array(
  'class'=>'CButtonColumn',
  ),
  ),
  )); */
?>
