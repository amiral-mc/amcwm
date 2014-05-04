<?php

$model = $contentModel->getParentContent();
$galleryId = $this->gallery->getParentContent()->gallery_id;
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $galleryId),
    AmcWm::t("msgsbase.core", "Videos") => array('/backend/multimedia/videos/index', 'gid' => $galleryId),
   AmcWm::t("amcTools", "Edit"),
);
$tools = array();
$tools[] = array('label' =>AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_video', 'image_id' => 'save');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/multimedia/videos/translate', 'gid' => $galleryId, 'id' => $model->video_id), 'id' => 'translate_gallery', 'image_id' => 'translate');
$tools[] = array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'url' => array('/backend/multimedia/videos/comments', 'gid' => $galleryId, 'item' => $model->video_id), 'id' => 'manage_videos_comments', 'image_id' => 'comments');
if ($this->getModule()->appModule->useDopeSheet) {
    $tools[] = array('label' => AmcWm::t("msgsbase.core", 'Dope Sheet'), 'url' => array('/backend/multimedia/videos/dopeSheet', 'gid' => $galleryId, 'mmId' => $model->video_id), 'id' => 'manage_videos_comments', 'image_id' => 'dope_sheet');
}
$tools[] = array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/videos/index', 'gid' => $galleryId), 'id' => 'videos_list', 'image_id' => 'back');
$this->sectionName = $contentModel->video_header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $tools));
?>

<?php echo $this->renderPartial('_form', array('contentModel' => $contentModel, 'formId' => $formId,)); ?>