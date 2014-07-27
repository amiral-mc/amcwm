<?php
$this->breadcrumbs = array(
    AmcWm::t($msgsBase, "Articles"),
);
$this->sectionName = AmcWm::t($msgsBase, "Manage Articles");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/articles/default/create'), 'id' => 'add_article', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_article', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Preview'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'view_article', 'image_id' => 'view'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_articles', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_articles', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_articles', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'comments', 'refId' => 'item'), 'id' => 'manage_article_comments', 'image_id' => 'comments'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'translate_article', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'articles_comments_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'articles_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<div class="search-form" style="display:none">
    <?php
    $view = $this->getModule()->appModule->getVirtualView("_search");
    $this->renderPartial($view, array(
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
                'name' => 'step_title',
                'htmlOptions' => array('width' => '100'),
                'header' => AmcWm::t("msgsbase.core", 'Workflow Step'),
                'visible' => AmcWm::app()->hasComponent("workflow"),
            ),
            array(
                'name' => 'article_id',
                'htmlOptions' => array('width' => '40'),
                'header' => AmcWm::t("msgsbase.core", 'Article'),
            ),
            array(
                'name' => 'create_date',
                'htmlOptions' => array('width' => '100'),
                'value' => 'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$data->create_date)',
                'header' => AmcWm::t("msgsbase.core", 'Creation Date'),
            ),
            array(
                'name' => 'article_header',
                'value' => '$data->article_header',
//                'htmlOptions' => array('width' => '250'),
                'header' => AmcWm::t("msgsbase.core", 'Article Header'),
            ),
            array(
                'name' => 'parent_article',
                'value' => 'isset($data->getParentContent()->parent_article)?$data->getParentContent()->parentArticle->getCurrent()->article_header:""',
//                'htmlOptions' => array('width' => '250'),
                'header' => AmcWm::t("msgsbase.core", 'Parent Article'),
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
                'name' => 'in_list',
                'value' => '($data->in_list) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '40', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'In List'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->published == ActiveRecord::PUBLISHED) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
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
                        'url' => 'Html::createUrl("/backend/articles/default/sort", array("id" => $data->article_id, "direction" => "up", "module"=>Yii::app()->request->getParam("module")))',
                    ),
                    'down' => array(
                        'label' => 'down',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/down.gif',
                        'url' => 'Html::createUrl("/backend/articles/default/sort", array("id" => $data->article_id, "direction" => "down", "module"=>Yii::app()->request->getParam("module")))',
                    ),
                ),
                'htmlOptions' => array('width' => '40', 'align' => 'center', 'class' => 'dataGridLinkCol'),
            ),
            array(
                'header' => AmcWm::t("amcwm.modules.logger.backend.messages.core", "Log Details"),
                'type' => 'html',
                'value' => 'Html::link(CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/log_view.png", "", array("border" => 0)), array("/backend/logger/default/index" , "itemId"=>$data->article_id, "from"=>"articles"), array("class"=>"log-link"))',
                'htmlOptions' => array('width' => '40', 'align' => 'center', 'class' => 'dataGridLinkCol'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => "articles_log_dialog",
    'options' => array(
        'title' => AmcWm::t("msgsbase.core", "Log Details"),
        'width' => 800,
        'height' => 600,
        'resizable' => false,
        'autoResize' => false,
        'autoOpen' => false,
        'iframe' => true,
        'modal' => true,
    ),
    'htmlOptions' => array("class" => "filemanager-wdg"),
));
$url = Html::createUrl('/backend/logger/default/index', array());
echo '<iframe class="filemanager-iframe" id="articles_log_dialog_iframe" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" title=""></iframe>';
$this->endWidget('zii.widgets.jui.CJuiDialog');



Yii::app()->clientScript->registerScript('popupVidew', "    
    $('.log-link').click(
        function(event){
            //event.preventDefault();                       
            $('#articles_log_dialog_iframe').attr('src', $(this).attr('href'));
            $('#articles_log_dialog').dialog( 'open' )
            return false;
        }        
    );    
");