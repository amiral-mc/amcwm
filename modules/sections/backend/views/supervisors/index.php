<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Sections") => array('/backend/sections/default/index'),
    AmcWm::t("msgsbase.core", "Supervisor"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/sections/supervisors/create'), 'id' => 'add_supervisor', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_supervisor', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_supervisors', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'supervisors_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'translate_sections', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/sections/default/index'), 'id' => 'sections_list', 'image_id' => 'back'),
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

    $dataProvider = $model->supervisors();
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
                'name' => 'person_id',
                'htmlOptions' => array('width' => '16'),
            ),
            array(
                'name' => 'name',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'value' => '$data->getParentContent()->getSexLabel()',
                'header' => AmcWm::t("msgsbase.core", 'Sex'),
            ),
            array(
                'value' => '$data->getParentContent()->email',
                 'header' => AmcWm::t("msgsbase.core", 'Email'),
            ),
            array(
                'value' => '$data->getParentContent()->country->getCountryName()',
                'header' => AmcWm::t("msgsbase.core", 'Country'),
            ),
            array(
                'name' => 'content_lang',
                'value' => '($data->content_lang) ? Yii::app()->params["languages"][$data->content_lang] : ""',
                'htmlOptions' => array('width' => '50'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>