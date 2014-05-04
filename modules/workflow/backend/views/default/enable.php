<div class="form">
    <?php
    $this->breadcrumbs = array(
        AmcWm::t("msgsbase.core", "Workflow"),
    );

    $this->sectionName = AmcWm::t("msgsbase.core", "Workflow");


    $this->widget('amcwm.core.widgets.tools.Tools', array(
        'id' => 'tools-grid',
        'items' => array(
            array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_polls', 'image_id' => 'publish'),
            array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_polls', 'image_id' => 'unpublish'),
            array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/workflow/default/index'), 'id' => 'polls_list', 'image_id' => 'back'),
        ),
        'htmlOptions' => array('style' => 'padding:5px;')
    ));

    $this->beginWidget('CActiveForm', array(
        'id' => Yii::app()->params["adminForm"],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));

    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'data-grid',
        'dataProvider' => $module,
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => AmcWm::t('msgsbase.core', 'Module Name'),
                'value' => '$data["title"]',
            ),
            array(
                'name' => AmcWm::t('msgsbase.core', 'Module'),
                'value' => '$data["module"]',
                'htmlOptions' => array('style' => 'width:110px; text-align:center')
            ),
            array(
//                'name' => 'workflow_enabled',
                'header' => AmcWm::t("msgsbase.core", 'Workflow enabled'),
                'value' => '($data["workflow_enabled"]) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
            ),
        )
    ));

    $this->endWidget();
    ?>
</div>