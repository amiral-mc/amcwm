<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Infocus")=>array('/backend/infocus/default/index'),
    AmcWm::t("msgsbase.core", "Edit"),
);
//$this->sectionName = AmcWm::t("msgsbase.core", "Update News");
$this->sectionName = $contentModel->header;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(        
        array('label' => AmcWm::t("msgsbase.core", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'edit_news', 'image_id'=>'save'),
        array('label' => AmcWm::t("msgsbase.core", 'View'), 'url' => array('/backend/infocus/default/view', 'id'=>$contentModel->infocus_id), 'id' => 'news_list', 'image_id'=>'view'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/infocus/default/index'), 'id' => 'news_list', 'image_id'=>'back'),
    ),    
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId'=>$formId)); ?>
