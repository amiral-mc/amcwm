<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.news", "Articles")=>array('/backend/articles/default/index'),
    AmcWm::t("msgsbase.sources", "Sources") => array('/backend/articles/sources/index'),
    AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t("msgsbase.sources", "Add Source");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_source', 'image_id' => 'save'),        
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/articles/sources/index'), 'id' => 'sources_list', 'image_id' => 'back'),
    ),
    'htmlOptions' => array('style' => 'padding:5px;')
));
?>
<?php $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId)); ?>