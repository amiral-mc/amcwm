<?php
$allOptions = $this->module->appModule->options;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/directory/default/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_dir', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'dir_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_company', 'image_id' => 'publish'),
        array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_company', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'translate_company', 'image_id' => 'translate'),
        array('visible' => $allOptions['system']['check']['branchesEnable'], 'label' => AmcWm::t("msgsbase.core", 'Branches'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'add_branch', 'image_id' => 'branches'),
        array('visible' => $allOptions['system']['check']['articlesEnable'], 'label' => AmcWm::t("msgsbase.core", 'Articles'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'companyArticles', 'refId' => 'companyId'), 'id' => 'add_article', 'image_id' => 'articles'),
        array('visible' => $allOptions['system']['check']['categoriesEnable'], 'label' => AmcWm::t("msgsbase.core", 'Categories'), 'url' => array('/backend/directory/categories/index'), 'id' => 'add_category', 'image_id' => 'add'),
        array('visible' => $allOptions['system']['check']['requestsEnable'], 'label' => AmcWm::t("msgsbase.core", 'Requests'), 'url' => array('/backend/directory/requests/index'), 'id' => 'requests', 'image_id' => 'requests'),
        array('visible' => $allOptions['default']['check']['allowUsersApply'], 'label' => AmcWm::t("msgsbase.core", 'Generate user'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'generateUser'), 'id' => 'generate_user', 'image_id' => 'permissions'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'dir_list', 'image_id' => 'back'),
    ),
));
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'contentModel' => $model,
        'allOptions' => $allOptions,
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
                'value' => '$data->company_id',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'company_id',
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            'company_name',
            array(
                'name' => AmcWm::t("msgsbase.core", "Nationality"),
                'value' => '($data->getParentContent()->nationality)?Yii::app()->getController()->getCountries(0, $data->getParentContent()->nationality):""',
                'htmlOptions' => array('width' => '100'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", "Email"),
                'value' => '$data->getParentContent()->email',
                'htmlOptions' => array('width' => '100'),
            ),
            array(
                'name' => AmcWm::t("msgsbase.core", "Category"),
                'value' => '($data->getParentContent()->category_id) ? $data->getParentContent()->category->getCurrent()->category_name : ""',
                'htmlOptions' => array('width' => '100'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->getParentContent()->published) ? CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20', 'align' => 'center'),
                'header' => AmcWm::t("msgsbase.core", 'Published'),
            ),
            array(
                'header' => AmcWm::t("msgsbase.core", 'Is Registered'),
                'type' => 'html',
                'visible' => $allOptions['system']['check']['requestsEnable'],
                'value' => '($data->getParentContent()->registered) ? Html::link(CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/preview.png", "", array("border" => 0)), array("/backend/directory/requests/view", "id"=>$data->company_id, "f"=>1)) : CHtml::image(AmcWm::app()->getController()->backendBaseUrl . "/images/no.png", "", array("border" => 0))',
                'htmlOptions' => array('width' => '60', 'align' => 'center'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>