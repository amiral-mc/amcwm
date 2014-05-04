<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Events"),
);

$this->sectionName = AmcWm::t("msgsbase.core", "Manage Events");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/events/default/create'), 'id' => 'add_poll', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_event', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_event', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_event', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_event', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'translate_event', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'searsh_event', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'events_list', 'image_id' => 'back'),
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
        'columns' => array(
           array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'event_id',
                'htmlOptions' => array('width' => '6'),
            ),
            array(
                'name' => 'event_header',
                'htmlOptions' => array('width' => '100'),
            ),
            array(
                'name' => 'event_date',
                'header' => AmcWm::t("msgsbase.core", 'Event Date'),
                'value' => 'Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $data->getParentContent()->event_date)',
                'htmlOptions' => array('width' => '50'),
            ),
            array(
                'name' => 'section_id',
                'header' => AmcWm::t("msgsbase.core", 'Section'),            
                'value' => '($data->getParentContent()->section_id && $data->getParentContent()->section->getCurrent()) ? $data->getParentContent()->section->getCurrent()->section_name : "--"',
                'htmlOptions' => array('width' => '80'),
            ),
            array(
                'value' => '$data->getParentContent()->country->getCountryName()',
                'header' => AmcWm::t("msgsbase.core", 'Country'),
                'htmlOptions' => array('width' => '80'),

            ),
            array(
                'name' => 'content_lang',
                'value' => '($data->content_lang) ? Yii::app()->params["languages"][$data->content_lang] : ""',
                'htmlOptions' => array('width' => '50'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->getParentContent()->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
        ),
        ),
    ));
    $this->endWidget();
    ?>

</div>