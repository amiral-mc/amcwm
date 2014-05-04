<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Issues")=>array('/backend/issues/default/index'),
    AmcWm::t("msgsbase.core", "Create"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Add Issue");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'add_news', 'image_id'=>'save'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/issues/default/index'), 'id' => 'news_list', 'image_id'=>'back'),
    ),    
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'formId'=>$formId)); ?>
