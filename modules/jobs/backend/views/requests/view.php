<?php
$mediaSettings = AmcWm::app()->appModule->mediaSettings;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Job requests") => array('/backend/jobs/default/index'),
    AmcWm::t("amcTools", "View"),
);
$this->sectionName = $model->name;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/jobs/requests/update', 'id' => $model->request_id), 'id' => 'edit_person', 'image_id' => 'edit'),
//        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/jobs/default/translate', 'id' => $model->request_id), 'id' => 'translate_exp', 'image_id' => 'translate'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/jobs/default/index'), 'id' => 'persons_list', 'image_id' => 'back'),
    ),
));
$militaryStatus = array(
    '0' => AmcWm::t('msgsbase.request', 'Completed'),
    '1' => AmcWm::t('msgsbase.request', 'Exempted'),
    '2' => AmcWm::t('msgsbase.request', 'Does Not Apply'),
    '3' => AmcWm::t('msgsbase.request', 'Currently Serving'),
    '4' => AmcWm::t('msgsbase.request', 'Postponed'),
);

$maritalStatus = array(
    '0' => AmcWm::t('msgsbase.request', 'Single'),
    '1' => AmcWm::t('msgsbase.request', 'Married'),
    '2' => AmcWm::t('msgsbase.request', 'Separated'),
    '3' => AmcWm::t('msgsbase.request', 'Divorced'),
);
$sex = NULL;
if($model->sex == "M"){
    $sex = AmcWm::t('msgsbase.request', 'Male');
}
elseif($model->sex == "F"){
    $sex = AmcWm::t('msgsbase.request', 'Female');
}
        $drawAttachLink = NULL;
        if ($model->request_id && $model->attach_ext) {
            if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['path'] . "/" . $model->request_id . "." . $model->attach_ext))) {
                $drawAttachLink = CHtml::link(Yii::t('msgsbase.request', 'Download'), Yii::app()->baseUrl . "/" . $mediaSettings['path'] . "/" . $model->request_id . "." . $model->attach_ext . "?" . time(), array('style' => 'color:#000; font-size:13px;'));
            }
        }

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'request_id',
        'name',
        'email',
        'phone',
        'city',
        'address',
        array(
            'name' => 'nationality',
            'value' => ($model->nationality) ? $model->nationalityCode->getCountryName() : NULL,
        ),
        array(
            'name' => 'sex',
            'value' => $sex,
        ),        
        array(
            'name' => 'car_owner',
            'value' => ($model->car_owner) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'driving_license',
            'value' => ($model->driving_license) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'have_children',
            'value' => ($model->have_children) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
         array(
            'name' => 'marital',
            'value' => isset($maritalStatus[$model->marital]) ? $maritalStatus[$model->marital] : NULL,
        ),
        array(
            'name' => 'military',
            'value' => isset($militaryStatus[$model->military]) ? $militaryStatus[$model->military] : NULL,
        ),
        'date_of_birth',
        'educations',
        'work_experiences',
        'computer_skills',
        'professional_certifications',
        'career_objective',
         array(
            'label' => AmcWm::t('msgsbase.request', 'CV File'),
             'type' => 'html',
            'value' => $drawAttachLink,
        ),
    ),
));
?>