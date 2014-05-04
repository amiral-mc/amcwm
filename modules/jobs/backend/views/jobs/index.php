<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Job requests") => array('/backend/jobs/default/index'),
    AmcWm::t("msgsbase.core", "Jobs"),
);
$this->sectionName = AmcWm::t("amcTools", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/jobs/jobs/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_glossary', 'image_id' => 'delete'),        
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'translate_exp', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'glossary_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("msgsbase.core", 'Jobs Categories'), 'url' => array('/backend/jobs/categories/index'), 'id' => 'add_category', 'image_id' => 'add'),
        array('label' =>AmcWm::t("msgsbase.core", 'Requests'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'requests', 'refId' => 'jid'), 'id' => 'manage_requests', 'image_id' => 'requests'),
        array('label' =>AmcWm::t("msgsbase.core", 'Short Listed'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'requests', 'refId' => 'jid'), 'id' => 'manage_short_list', 'image_id' => 'requests'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/jobs/default/index'), 'id' => 'glossary_list', 'image_id' => 'back'),
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
    $this->beginWidget('CActiveForm', array(
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
                'name' => 'job_id',
                'htmlOptions' => array('width' => '16'),
            ),
            array(
                'name' => 'job',
                'htmlOptions' => array(),
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