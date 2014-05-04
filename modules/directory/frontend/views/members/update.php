<?php
$formId = Yii::app()->params["adminForm"];
$breadcrumbs[AmcWm::t("msgsbase.core", "Member Area")] = array('/users/default/index');
$breadcrumbs[AmcWm::t("msgsbase.core", "_manage_company_")] = array('/directory/members/index');
$breadcrumbs[] =  AmcWm::t("amcTools", "Edit");
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Directory") => array('/backend/directory/default/index'),
    AmcWm::t("amcTools", "Edit"),
);
$pageContent = $this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId'=>$formId), 'id' => 'edit_company', 'image_id'=>'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/directory/members/index'), 'id' => 'view_company', 'image_id' => 'back'),
    ),    
), true);
?>
<?php 
$pageContent .= $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId'=>$formId), true); 
$this->widget('PageContentWidget', array(
    'id' => 'update_company',
    'contentData' => $pageContent,
    'title' => AmcWm::t("msgsbase.core", '_manage_company_'),
    'image' => null,
    'breadcrumbs' => $breadcrumbs,
));
?>