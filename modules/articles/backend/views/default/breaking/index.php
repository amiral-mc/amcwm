<?php
$this->breadcrumbs = array(
   AmcWm::t($msgsBase, "Articles"),
);

$this->sectionName =AmcWm::t($msgsBase, "Manage Articles");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/articles/default/create'), 'id' => 'add_article', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_article', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.breaking", 'Details'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'more',), 'id' => 'manage_article', 'image_id' => 'articles'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_articles', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_articles', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_articles', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("msgsbase.sources", 'Sources'), 'url' => array('/backend/articles/default/sources'), 'id' => 'sources_list', 'image_id' => 'listing'),        
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'translate_article', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'articles_comments_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'articles_list', 'image_id' => 'back'),
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
//    $pageSize = 10;
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
                'name' => 'article_id',
                'htmlOptions' => array('width' => '40'),
                'header' => AmcWm::t("msgsbase.core", 'Article'),
            ),
            array(
                'name' => 'create_date',
                'htmlOptions' => array('width' => '100'),
                'value' => 'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$data->getParentContent()->create_date)',
                'header' => AmcWm::t("msgsbase.core", 'Creation Date'),
            ),
            array(
                'name' => 'article_header',
                'value' => '$data->article_header',
//                'htmlOptions' => array('width' => '250'),
                'header' => AmcWm::t("msgsbase.core", 'Article Header'),
            ),          
            array(
                'name' => 'section_id',
                'value' => '($data->getParentContent()->section_id && $data->getParentContent()->section->getCurrent()) ? $data->getParentContent()->section->getCurrent()->section_name : "--"',
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
                'name' => 'in_list',
                'value' => '($data->getParentContent()->in_list) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '40', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'In List'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->getParentContent()->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Published'),
            ),                      
        )
    ));
    $this->endWidget();
    ?>
</div>

<?php /* $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'articles-grid',
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
