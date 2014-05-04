<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Job requests"),
);
$this->sectionName = AmcWm::t("amcTools", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
//        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/jobs/default/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Preview'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'preview_person', 'image_id' => 'view'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_jobs', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'jobs_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("msgsbase.core", 'Jobs'), 'url' => array('/backend/jobs/jobs/index'), 'id' => 'add_category', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/jobs/default/index'), 'id' => 'jobs_list', 'image_id' => 'back'),
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
                'name' => 'request_id',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", "Job"),
                'value' => 'isset($data->job->job_id)?$data->job->getCurrent()->job:"--"',
                'htmlOptions' => array(),
            ),
            array(
                'name' => 'name',
                'htmlOptions' => array(),
            ),
            array(
                'name' => 'sex',
                'htmlOptions' => array(),
            ),
            array(
                'name' => 'email',
                'htmlOptions' => array(),
            ),
            array(
                'name' => 'marital',
                'htmlOptions' => array(),
            ),
            array(
                'name' => 'driving_license',
                'htmlOptions' => array(),
            ),
            array(
                'name' => 'phone',
                'htmlOptions' => array(),
            ),
            array(
                'header' => AmcWm::t("msgsbase.core", 'Short List'),
                'class' => 'CButtonColumn',
                'template' => '{accept}',
                'buttons' => array(
                    'accept' => array(
                        'label' => 'accept',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/up.gif',
                        'url' => 'Html::createUrl("/backend/jobs/requests/shortlist", array("id" => $data->request_id, "direction" => "up", "module"=>Yii::app()->request->getParam("module")))',
                    ),
                ),
                'htmlOptions' => array('width' => '40', 'align' => 'center', 'class' => 'dataGridLinkCol'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>