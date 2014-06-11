<?php

$mediaSettings = $this->module->appModule->mediaSettings;
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Tenders") => array('/backend/tenders/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);
$this->sectionName = $contentModel->title;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/tenders/default/create'), 'id' => 'add_person', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/tenders/default/update', 'id' => $model->tender_id), 'id' => 'edit_person', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/tenders/default/translate', 'id' => $model->tender_id), 'id' => 'translate_company', 'image_id' => 'translate'),
        array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'url' => array('/backend/tenders/default/comments', 'item' => $model->tender_id), 'id' => 'news_comments', 'image_id' => 'comments'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/tenders/default/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));


$drawDocLink = NULL;


if ($model->tender_id && $model->file_ext && is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['files']['path'] . "/" . $model->tender_id . "." . $model->file_ext))) {
    $drawDocLink = '<a href="' . $this->createUrl('/site/download', array('f' => "{$mediaSettings['paths']['files']['path']}/{$model->tender_id}.{$model->file_ext}")) . '">' . AmcWm::t("msgsbase.core", 'Download the file') . '</a>';
}


$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'tender_id',
        array(
            'name' => AmcWm::t("msgsbase.core", "Department"),
            'value' => ($contentModel->getParentContent()->department_id) ? $contentModel->getParentContent()->department->getCurrent()->department_name : "",
            'htmlOptions' => array('width' => '230'),
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Title"),
            'value' => $contentModel->title,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Tender Type"),
            'value' => $model->tender_type,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Tender Status"),
            'value' => $model->tender_status,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Rfp Start Date"),
            'value' => $model->rfp_start_date,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Submission Start Date"),
            'value' => $model->submission_start_date,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Rfp Price1"),
            'value' => ($model->rfp_price1) ? $model->rfp_price1 . " " . AmcWm::app()->getLocale()->getCurrencySymbol($model->rfp_price1_currency) : null,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Rfp Price2"),
            'value' => ($model->rfp_price2) ? $model->rfp_price2 . " " . AmcWm::app()->getLocale()->getCurrencySymbol($model->rfp_price2_currency) : null,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Primary Insurance"),
            'value' => ($model->primary_insurance) ? $model->primary_insurance . " " . AmcWm::app()->getLocale()->getCurrencySymbol($model->primary_insurance_currency) : null,
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Description"),
            'value' => $contentModel->description,
            'type' => 'html',
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Conditions"),
            'value' => $contentModel->conditions,
            'type' => 'html',
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Notes"),
            'value' => $contentModel->notes,
            'type' => 'html',
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Technical Results Data"),
            'value' => $contentModel->technical_results,
            'type' => 'html',
        ),
        array(
            'name' => AmcWm::t("msgsbase.core", "Financial Results Data"),
            'value' => $contentModel->financial_results,
            'type' => 'html',
        ),
        array(
            'name' => 'docFile',
            'type' => 'html',
            'value' => ($model->file_ext) ? $drawDocLink : AmcWm::t("amcBack", "No"),
        ),
    ),
));
?>