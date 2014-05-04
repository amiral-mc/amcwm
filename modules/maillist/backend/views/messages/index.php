<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.mailing", "Maillist Message"),
);
$channelId = ($this->channel) ? $this->channel->id : null;
$this->sectionName = AmcWm::t("msgsbase.core", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/maillist/messages/create', 'cid' => $channelId), 'id' => 'add_maillist', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('cid' => $channelId)), 'id' => 'edit_maillist', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('cid' => $channelId)), 'id' => 'delete_maillist', 'image_id' => 'delete'),
        array('label' =>AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('cid' => $channelId)), 'id' => 'publish_maillist', 'image_id' => 'publish'),
        array('label' =>AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('cid' => $channelId)), 'id' => 'unpublish_maillist', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('cid' => $channelId)), 'id' => 'maillist_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/maillist/channels/index'), 'id' => 'maillist_list', 'image_id' => 'back'),
    ),
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
                'name' => 'subject',
                'htmlOptions' => array('style' => 'direction:ltr; text-align:right'),
            ),
            array(
                'htmlOptions' => array('width' => '100', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.mailing",  'Last Send Date'),
                'value'=>'$data->lastSent',
            ),
            array(
                'name' => 'published',
                'value' => '($data->published == ActiveRecord::PUBLISHED) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Published'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>