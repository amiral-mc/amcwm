<?php
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $this->gallery->gallery_id),
    AmcWm::t("msgsbase.core", "Videos") => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id),
    $model->video_header => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id, 'id' => $model->video_id),
);


$this->sectionName = AmcWm::t("msgsbase.core", "Dope Sheet");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Edit'), 'url' => array('/backend/multimedia/videos/dopeSheet', 'gid' => $this->gallery->gallery_id, 'mmId' => $model->video_id), 'id' => 'manage_videos_comments', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/multimedia/videos/index', 'gid' => $this->gallery->gallery_id), 'id' => 'videos_list', 'image_id' => 'back'),
    ),
));
?>

<?php
$shots = "";
if(count($model->getDopeSheet()->dopeSheetShots)){
    $shots = '<table border="0" cellpadding="2" cellspasing ="0">';
    $shots .= '<tr>';
    $shots .= '<th>' . AmcWm::t("msgsbase.core", "Shot Type") . '</th>';
    $shots .= '<th>' . AmcWm::t("msgsbase.core", "Shot Description") . '</th>';
    $shots .= '<th>' . AmcWm::t("msgsbase.core", "Sound") . '</th>';
    $shots .= '<th>' . AmcWm::t("msgsbase.core", "Sound length mm:ss") . '</th>';
    $shots .= '</tr>';
    foreach ($model->getDopeSheet()->dopeSheetShots as $i => $shot){
        $shots .= '<tr>';
        $shots .= '<td>' . $shot->type->type . '</td>';
        $shots .= '<td>' . $shot->description . '</td>';
        $shots .= '<td>' . $shot->sound . '</td>';
        $shots .= '<td>' . $shot->timeLength . '</td>';
        $shots .= '</tr>';
    }
    $shots .='</table>';
}
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model->getDopeSheet(),
    'attributes' => array(
        array(
            'name' => 'published',
            'value' => ($model->getDopeSheet()->published) ? AmcWm::t("amcFront", "Yes") : AmcWm::t("amcFront", "No"),
        ),
        'reporter',
        'source',
        'location',
        array(
            'name' => 'event_date',
            'value' => Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->getDopeSheet()->event_date),
        ),
        'sound',
        'timeLength',
        array(
            'type'=>'html',
            'label'=> AmcWm::t("msgsbase.core", "Shots"),
            'value'=>$shots,
        ),
        array(
            'type'=>'html',
            'name'=>'story',
        )
    ),
));
?>