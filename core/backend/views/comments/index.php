<?php
$this->breadcrumbs[] = AmcWm::t("amcwm.core.backend.messages.comments", "Comments");
$this->sectionName = AmcWm::t("amcwm.core.backend.messages.comments", "Manage Comments");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => $this->getParams()), 'id' => 'edit_comment', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Preview'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => $this->getParams()), 'id' => 'edit_comment', 'image_id' => 'view'),
        array('label' => AmcWm::t("amcwm.core.backend.messages.comments", 'Replies'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'replies', 'params' => $this->getParams(), 'refId' => 'cid'), 'id' => 'manage_comment_replies', 'image_id' => 'replies'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => $this->getParams()), 'id' => 'delete_comments', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => $this->getParams()), 'id' => 'publish_comments', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => $this->getParams()), 'id' => 'unpublish_comments', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcwm.core.backend.messages.comments", 'Hide'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'hide', 'params' => array_merge(array('hidden' => 1), $this->getParams()), 'many' => true), 'id' => 'hide_comments', 'image_id' => 'hide'),
        array('label' => AmcWm::t("amcwm.core.backend.messages.comments", 'Show'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'hide', 'params' => array_merge(array('hidden' => 0), $this->getParams()), 'many' => true), 'id' => 'unhide_comments', 'image_id' => 'show'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'comments_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => $this->backRoute, 'id' => 'back_2_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<div class="search-form" style="display:none">
<?php
$this->renderPartial("{$this->viewAlias}._search", array(
    'model' => $model->comments,
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
            'value' => '$data->comments->comment_id',
        ),
        array(
            'name' => 'comment_id',
            'value' => '$data->comments->comment_id',
            'htmlOptions' => array('width' => '16'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Comments ID'),
        ),
        array(
            'name' => 'comment_header',
            'value' => '$data->comments->comment_header',
            'htmlOptions' => array('width' => '250'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Comment Header'),
        ),
        array(
            'name' => 'user_id',
            'value' => '($data->comments->user_id)?$data->comments->user->username:(($data->comments->commentsOwners)?$data->comments->commentsOwners->name:"---")',
            'htmlOptions' => array('width' => '100'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Writer'),
        ),
        array(
            'name' => 'ip',
            'value' => '$data->comments->ip',
            'htmlOptions' => array('width' => '100'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'IP'),
        ),
        array(
            'name' => 'comment_date',
            'value' => 'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$data->comments->comment_date)',
            'htmlOptions' => array('width' => '100'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Comment Date'),
        ),
        array(
            'name' => 'comment_review',
            'value' => 'count($data->comments->comments)',
            'htmlOptions' => array('width' => '100'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Replies Count'),
        ),
        array(
            'name' => 'bad_imp',
            'value' => '$data->comments->bad_imp',
            'htmlOptions' => array('width' => '100'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Bad Imp'),
        ),
        array(
            'name' => 'hide',
            'value' => '($data->comments->hide) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
            'type' => 'html',
            'htmlOptions' => array('width' => '20', 'align' => 'center'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Hide'),
        ),
        array(
            'name' => 'force_display',
            'value' => '($data->comments->force_display) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
            'type' => 'html',
            'htmlOptions' => array('width' => '20', 'align' => 'center'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Force Display'),
        ),
        array(
            'name' => 'published',
            'value' => '($data->comments->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
            'type' => 'html',
            'htmlOptions' => array('width' => '20', 'align' => 'center'),
            'header' => AmcWm::t("amcwm.core.backend.messages.comments", 'Published'),
        ),
    )
));
$this->endWidget();
?>
</div>