<?php
$formId = Yii::app()->params["adminForm"];
$mediaPaths = AmcWm::app()->getController()->getModule()->appModule->mediaPaths;
$model = $contentModel->getParentContent();
$galleryId = $this->gallery->getParentContent()->gallery_id;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $galleryId),
    AmcWm::t("msgsbase.core", "Videos") => array('/backend/multimedia/videos/index', 'gid' => $galleryId),
   AmcWm::t("amcTools", "View"),
);

$this->sectionName = $contentModel->video_header;
$tools = array();
$tools[] = array('label' =>AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/multimedia/videos/create', 'gid' => $galleryId), 'id' => 'add_video', 'image_id' => 'add');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/multimedia/videos/update', 'gid' => $galleryId, 'id' => $model->video_id), 'id' => 'edit_video', 'image_id' => 'edit');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/multimedia/videos/translate', 'gid' => $galleryId, 'id' => $model->video_id), 'id' => 'translate_gallery', 'image_id' => 'translate');
if ($this->getModule()->appModule->useDopeSheet) {
    $tools[] = array('label' => AmcWm::t("msgsbase.core", 'Dope Sheet'), 'url' => array('/backend/multimedia/videos/dopeSheet', 'gid' => $galleryId, 'mmId' => $model->video_id), 'id' => 'manage_videos_comments', 'image_id' => 'dope_sheet');
}
$tools[] = array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'url' => array('/backend/multimedia/videos/comments', 'gid' => $galleryId, 'item' => $model->video_id), 'id' => 'manage_videos_comments', 'image_id' => 'comments');
$tools[] = array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/videos/index', 'gid' => $galleryId), 'id' => 'videos_list', 'image_id' => 'back');
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => $tools,
));
?>

<div style="padding: 5px;">
    <?php
    $video = NULL;
    if ($model->videoType == "externalVideos") {
        $video = $model->videoURL;
    } else if ($model->videoType == "internalVideos") {
        $video = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->baseUrl ."/" . $mediaPaths['videos']['path']) . "/" . $model->video_id . "." . $model->internalVideos->video_ext;                                    
        $video = str_replace("{gallery_id}", $model->gallery_id, $video);                                    
    }
    if ($video) {
        $this->widget('amcwm.widgets.videoplayer.VideoPlayer', array(
            'id' => 'videoPlayer1',
            'className' => 'videoPlayerClass',
            'width' => 400,
            'height' => 300,
            'title' => 'Your video header text',
            'video' => $video,
                )
        );
    }
    ?>
</div>
<?php
$infocusName = $this->getInfocucName($model->infocusId);
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'video_id',
        array(
            'label' => AmcWm::t("msgsbase.core", 'Video Header'),
            'value' => $contentModel->video_header,
        ),
        array(
            'name' => 'gallery_id',
            'value' => $this->gallery->gallery_header,
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Description'),
            'value' => $contentModel->description,
            'type' => 'html',
        ),
        array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcFront", "Yes") : AmcWm::t("amcFront", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Tags'),
            'value' => nl2br($contentModel->tags),
            'type' => 'html',
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
            'label' => AmcWm::t("msgsbase.core", "Content Lang"),
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'In Focus File'),
            'value' => $infocusName,
        ),
    ),
));
?>
