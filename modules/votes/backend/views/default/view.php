<?php
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
   AmcWm::t("msgsbase.core", "Votes Questions") => array('/backend/votes/default/index'),  
   AmcWm::t("amcTools", "View"),
);
$this->sectionName = $contentModel->ques;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/votes/default/create'), 'id' => 'add_poll', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/votes/default/update' ,  'id' => $model->ques_id), 'id' => 'edit_poll', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/votes/default/translate', 'id' => $model->ques_id), 'id' => 'translate_person', 'image_id' => 'translate'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/votes/default/index'), 'id' => 'polls_list', 'image_id' => 'back'),
    ),
));
$answers = "";
foreach ($contentModel->votesOptions as $answer) {
    $answers .= $answer->value . "<br />";
}
?>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'ques_id',
        array(
            'label' => AmcWm::t("msgsbase.core", "Question"),
            'value' => $contentModel->ques,
        ),        
        array(
            'label' => AmcWm::t("msgsbase.core", "Answers"),
            'value' => $answers,
            'type'=>'html',
        ),        
        array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'suspend',
            'value' => ($model->suspend) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'creation_date',
            'value' => Yii::app()->dateFormatter->format("dd/MM/y", $model->creation_date),
        ),
        array(
            'name' => 'publish_date',
            'value' => Yii::app()->dateFormatter->format("dd/MM/y", $model->publish_date),
        ),
        array(
            'name' => 'expire_date',
            'value' => ($model->expire_date) ? Yii::app()->dateFormatter->format("dd/MM/y", $model->expire_date) : NULL,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
        ),
    ),
));
?>
