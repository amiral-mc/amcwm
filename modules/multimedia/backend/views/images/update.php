<?php
$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs=array(
AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $this->gallery->gallery_id),
    AmcWm::t("msgsbase.core",  "_{$this->getId()}_title_") => array('/backend/multimedia/'.$this->getId().'/index', 'gid' => $this->gallery->gallery_id),
   AmcWm::t("amcTools", "Edit"),
);

$this->sectionName = $contentModel->image_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_image', 'image_id' => 'save'),
        array('label' =>AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/multimedia/'.$this->getId().'/translate', 'gid' => $this->gallery->gallery_id, 'id' => $model->image_id), 'id' => 'translate_gallery', 'image_id' => 'translate'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/'.$this->getId().'/index', 'gid' => $this->gallery->gallery_id), 'id' => 'images_list', 'image_id' => 'back'),
    ),    
));
?>
<?php echo $this->renderPartial(AmcWm::app()->appModule->getViewPathAlias() . '.images._form', array('contentModel' => $contentModel, 'formId' => $formId,)); ?>