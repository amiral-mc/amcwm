<?php
$toolsItems = array();
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/sections/default/create', 'sid' => $this->getParentId()), 'id' => 'add_section', 'image_id' => 'add');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('sid' => $this->getParentId())), 'id' => 'edit_section', 'image_id' => 'edit');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('sid' => $this->getParentId())), 'id' => 'delete_sections', 'image_id' => 'delete');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Publish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('sid' => $this->getParentId())), 'id' => 'publish_sections', 'image_id' => 'publish');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Unpublish'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('sid' => $this->getParentId())), 'id' => 'unpublish_sections', 'image_id' => 'unpublish');
$toolsItems[] = array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('sid' => $this->getParentId())), 'id' => 'translate_sections', 'image_id' => 'translate');

if ($this->getModule()->appModule->useSupervisor) {
    $toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Supervisors'), 'url' => array('/backend/sections/default/supervisors'), 'id' => 'sections_supervisors', 'image_id' => 'users');
}
//if (!$this->getParentId()) {
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Sub-Sections'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'index', 'refId' => 'sid'), 'id' => 'sub_sections', 'image_id' => 'sub-sections');
//}
$toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'params' => array('sid' => $this->getParentId())), 'id' => 'sections_search', 'image_id' => 'search');


if ($this->getParentId()) {
    $sections = Sections::getTree($this->getParentId());
    $this->breadcrumbs[AmcWm::t("msgsbase.core", "Sections")] = array('/backend/sections/default/index');
    foreach ($sections as $section) {
        $this->breadcrumbs[$section['section_name']] = array('/backend/sections/default/view', 'id' => $section['section_id'], 'sid' => $section['parent_section']);
    }
    if (count($sections) > 1) {
        $prevSection = $sections[count($sections) - 1];
        $backSection = $prevSection['parent_section'];
    } else {
        $backSection = null;
    }
    $toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/sections/default/index', 'sid'=>$backSection), 'id' => 'modules_list', 'image_id' => 'back');
} else {
    $this->breadcrumbs[] = AmcWm::t("msgsbase.core", "Sections");
    $toolsItems[] = array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'modules_list', 'image_id' => 'back');
}
$dataProvider = $model->search();
$this->sectionName = AmcWm::t("msgsbase.core", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $toolsItems,
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
                'name' => 'section_id',
                'htmlOptions' => array('width' => '16'),
            ),
            array(
                'name' => 'section_name',
                'htmlOptions' => array('width' => '230'),
            ),
            array(
                'value' => '($data->supervisor && $data->supervisorPerson->getTranslated($data->content_lang)) ? $data->supervisorPerson->getTranslated($data->content_lang)->name : ""',
                'htmlOptions' => array('width' => '100'),
                'header' => AmcWm::t("msgsbase.core", 'Supervisor'),
                'visible'=>$this->getModule()->appModule->useSupervisor,
            ),
            array(
                'name' => 'content_lang',
                'value' => '($data->content_lang) ? Yii::app()->params["languages"][$data->content_lang] : ""',
                'htmlOptions' => array('width' => '50'),
            ),
            array(
                'value' => '($data->getParentContent()->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'header' => AmcWm::t("msgsbase.core", 'Published'),
                'htmlOptions' => array('width' => '20'),
            ),
            array(
                'header' => AmcWm::t("amcTools", 'Sort'),
                'class' => 'CButtonColumn',
                'template' => '{up} {down}',
                'buttons' => array(
                    'up' => array(
                        'label' => 'up',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/up.gif',
                        'url' => 'Html::createUrl("/backend/sections/default/sort", array("id" => $data->section_id, "sid"=>$data->getParentContent()->parent_section, "direction" => "up",))',
                    ),
                    'down' => array(
                        'label' => 'down',
                        'imageUrl' => Yii::app()->request->baseUrl . '/images/down.gif',
                        'url' => 'Html::createUrl("/backend/sections/default/sort", array("id" => $data->section_id, "sid"=>$data->getParentContent()->parent_section,"direction" => "down",))',
                    ),
                ),
                'htmlOptions' => array('width' => '40', 'align' => 'center', 'class' => 'dataGridLinkCol'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>