<?php
$breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Member Area") => array('/users/default/index'),
    AmcWm::t($msgsBase, "Articles"),
);
$pageContent = $this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/articles/manage/create'), 'id' => 'add_article', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_article', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_articles', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_articles', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_articles', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'translate_article', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'articles_comments_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/directory/members/index'), 'id' => 'view_company', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
        ), true);
$this->beginClip('clipForm');
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
                'htmlOptions' => array('width' => '250'),
                'header' => AmcWm::t("msgsbase.core", 'Article Header'),
            ),
//            array(
//                'name' => 'parent_article',
//                'value' => 'isset($data->getParentContent()->parent_article)?$data->getParentContent()->parentArticle->getCurrent()->article_header:""',
////                'htmlOptions' => array('width' => '250'),
//                'header' => AmcWm::t("msgsbase.core", 'Parent Article'),
//            ),
//            array(
//                'name' => 'section_id',
//                'value' => '($data->getParentContent()->section_id && $data->getParentContent()->section->getCurrent()) ? $data->getParentContent()->section->getCurrent()->section_name : "--"',
//                'htmlOptions' => array('width' => '60', 'align' => 'center'),
//                'header' => AmcWm::t("msgsbase.core", 'Section'),
//            ),
            array(
                'name' => 'published',
                'value' => '($data->getParentContent()->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
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
                        'url' => 'Html::createUrl("/articles/manage/sort", array("id" => $data->article_id, "direction" => "up", "module"=>Yii::app()->request->getParam("module")))',
                    ),
                    'down' => array(
                        'label' => 'down',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/down.gif',
                        'url' => 'Html::createUrl("/articles/manage/sort", array("id" => $data->article_id, "direction" => "down", "module"=>Yii::app()->request->getParam("module")))',
                    ),
                ),
                'htmlOptions' => array('width' => '40', 'align' => 'center', 'class' => 'dataGridLinkCol'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>

<?php
$this->endClip('clipForm');
$pageContent.=$this->clips['clipForm'];
$this->widget('PageContentWidget', array(
    'id' => 'translae_company',
    'contentData' => $pageContent,
    'title' => AmcWm::t($msgsBase, '_manage_news_'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>