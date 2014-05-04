<?php
$listRoute = array_merge( array("/". str_replace($this->getAction()->getId(), "index", $this->getRoute())), $this->getParams());
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs[AmcWm::t("amcwm.core.backend.messages.comments", "Comments")] = $this->backRoute;
$this->breadcrumbs[$this->comment->comment_header] = $listRoute;
$this->breadcrumbs[] = AmcWm::t("amcwm.core.backend.messages.comments", "Update");
$this->sectionName = $model->comment_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(        
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'edit_comment', 'image_id'=>'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => $listRoute, 'id' => 'back_2_list', 'image_id' => 'back'),
        
    ),    
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<?php echo $this->renderPartial("{$this->viewAlias}._form", array('model' => $model, 'formId'=>$formId)); ?>
