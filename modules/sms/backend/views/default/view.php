<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Multimeda sms news") => array('/backend/sms/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);

$this->sectionName =  $model->video_header;


$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Edit'), 'url' => array('/backend/sms/default/update', $model->ext, 'id' => $model->video_id), 'id' => 'edit_video', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/sms/default/index', $model->ext), 'id' => 'videos_list', 'image_id' => 'back'),
    ),
));
?>

<div style="padding: 5px;">
    <?php
    $videoUrl = NULL;
    $videoFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . Yii::app()->params["multimedia"]['smsVideos']['path']) . "/" . $model->getVideoName();
    if (is_file($videoFile)) {
        $video = Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . "/" . Yii::app()->params["multimedia"]['smsVideos']['path'] . "/" . $model->getVideoName();
        $videoUrl = CHtml::link($video, $video, array('target' => '_bkank'));
    }
    ?>
</div>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'video_id',
        'video_header',
        array(
            'label' => AmcWm::t("msgsbase.core", 'Video File'),
            'value' => $videoUrl,
            'type' => 'html',
        ),
        array(
            'name' => 'description',
            'type' => 'html',
        ),
        array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'creation_date',
            'value' => Yii::app()->dateFormatter->format("dd/MM/y", $model->creation_date),
        ),
        array(
            'name' => 'content_lang',
            'value' => ($model->content_lang) ? Yii::app()->params["languages"][$model->content_lang] : "",
        ),
    ),
));
?>
