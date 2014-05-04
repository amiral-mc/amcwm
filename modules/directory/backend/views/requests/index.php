<?php
$allOptions = $this->module->appModule->options;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/default/index'),
    AmcWm::t("msgsbase.core", "Requests"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_compamy', 'image_id' => 'delete'),
//        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'glossary_search', 'image_id' => 'search'),        
        //array('label' => AmcWm::t("msgsbase.core", '_accept_'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_company', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/directory/default/index'), 'id' => 'companies_list', 'image_id' => 'back'),
    ),
));
?>

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

    $dataProvider = $model->requests();
    $dataProvider->pagination->pageSize = Yii::app()->params["pageSize"];
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'data-grid',
        'dataProvider' => $dataProvider,
        'selectableRows' => Yii::app()->params["pageSize"],
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'value' => '$data->company_id',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'company_id',
                'htmlOptions' => array('width' => '16'),
            ),
            'company_name',
            array(
                'name' => AmcWm::t("msgsbase.core", "Nationality"),
                'value' => '($data->getParentContent()->nationality)?Yii::app()->getController()->getCountries(0, $data->getParentContent()->nationality):""',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", "Email"),
                'value' => '$data->getParentContent()->email',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", "Category"),
                'value' => '($data->getParentContent()->category_id) ? $data->getParentContent()->category->getCurrent()->category_name : ""',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'value' => '($data->getParentContent()->accepted == DirCompanies::SUSPENDED) ? CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Suspended'),
            ),
            array(
                'value' => '($data->getParentContent()->accepted == DirCompanies::ACCEPTED) ? CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Accepted'),
            ),
            array(
                'value' => '($data->getParentContent()->accepted == DirCompanies::DENIED) ? CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Denied'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>