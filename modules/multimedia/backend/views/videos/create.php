<?php
$galleryId = $this->gallery->getParentContent()->gallery_id;
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $galleryId),
    AmcWm::t("msgsbase.core", "Videos") => array('/backend/multimedia/videos/index', 'gid' => $galleryId),
   AmcWm::t("amcTools", "Create"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Create Video");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'add_video', 'image_id' => 'save'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/videos/index', 'gid' => $galleryId), 'id' => 'videos_list', 'image_id' => 'back'),
    ),
));
?>


<?php echo $this->renderPartial('_form', array('contentModel'=>$contentModel, 'formId'=>$formId)); ?>