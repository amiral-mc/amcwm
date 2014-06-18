<?php

$model = $contentModel->getParentContent();
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Galleries") => array('/backend/multimedia/default/index'),
    $this->gallery->gallery_header => array('/backend/multimedia/default/view', 'id' => $this->gallery->gallery_id),
    AmcWm::t("msgsbase.core",  "_{$this->getId()}_title_") => array('/backend/multimedia/'.$this->getId().'/index', 'gid' => $this->gallery->gallery_id),
   AmcWm::t("amcTools", "View"),
);

$this->sectionName = $contentModel->image_header;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' =>AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/multimedia/'.$this->getId().'/create', 'gid' => $this->gallery->gallery_id), 'id' => 'add_image', 'image_id' => 'add'),
        array('label' =>AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/multimedia/'.$this->getId().'/update', 'gid' => $this->gallery->gallery_id, 'id' => $model->image_id), 'id' => 'edit_image', 'image_id' => 'edit'),
        array('label' =>AmcWm::t("amcTools", 'Translate'), 'url' => array('/backend/multimedia/'.$this->getId().'/translate', 'gid' => $this->gallery->gallery_id, 'id' => $model->image_id), 'id' => 'translate_gallery', 'image_id' => 'translate'),
        array('label' => AmcWm::t("msgsbase.core", 'Comments'), 'url' => array('/backend/multimedia/'.$this->getId().'/comments', 'gid' => $this->gallery->gallery_id, 'item' => $model->image_id), 'id' => 'manage_images_comments', 'image_id' => 'comments'),
        array('label' =>AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/multimedia/'.$this->getId().'/index', 'gid' => $this->gallery->gallery_id), 'id' => 'images_list', 'image_id' => 'back'),
    ),
));
?>

<?php

$infocusName = $this->getInfocucName($model->infocusId);
$drawImage = Yii::app()->baseUrl . "/" . Yii::app()->getController()->imageInfo['path'] . "/" . $model->image_id . "." . $model->ext . "?" . time();
$drawImage = str_replace("{gallery_id}", $model->gallery_id, $drawImage);
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'image_id',
        array(
            'name' => AmcWm::t("msgsbase.core", "Photo"),
            'type' => 'html',
            'value' => Chtml::image($drawImage, "", array("width" => 400))
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Image Header'),
            'value' => $contentModel->image_header,
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
            'visible'=> $this->getModule()->appModule->useInfocus,
        ),
    ),
));
?>
