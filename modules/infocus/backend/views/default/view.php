<?php
$mediaSettings = AmcWm::app()->appModule->mediaSettings;
$options = $this->module->appModule->options;
$useBackground = isset($options['system']['check']['useBackground']) && $options['system']['check']['useBackground'];
$useBanner = isset($options['system']['check']['useBanner']) && $options['system']['check']['useBanner'];
$model = $contentModel->getParentContent();
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Infocus")=>array('/backend/infocus/default/index'),
    AmcWm::t("msgsbase.core", "View"),
);

$this->sectionName = $contentModel->header;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("msgsbase.core", 'Create'), 'url' => array('/backend/infocus/default/create'), 'id' => 'add_news', 'image_id' => 'add'),
        array('label' => AmcWm::t("msgsbase.core", 'Edit'), 'url' => array('/backend/infocus/default/update' , 'id' => $model->infocus_id), 'id' => 'edit_article', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.core", 'Back'), 'url' => array('/backend/infocus/default/index'), 'id' => 'news_list', 'image_id'=>'back'),
    ),
));
?>

<?php
    $drawImage = NULL;
    if ($model->infocus_id && $model->thumb) {
        if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['list']['path'] . "/" . $model->infocus_id . "." . $model->thumb))) {
            $drawImage = '<div>'.CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['list']['path'] . "/" . $model->infocus_id . "." . $model->thumb . "?" . time(), "", array("class" => "image")).'</div>';
        }
    }

    $drawBackground = NULL;
    if ($model->infocus_id && $model->background) {
        if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['backgrounds']['path'] . "/" . $model->infocus_id . "." . $model->background))) {
            $drawBackground = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['backgrounds']['path'] . "/" . $model->infocus_id . "." . $model->background . "?" . time(), "", array("class" => "image", "width" => "100")) . '</div>';
        }
    }
    //echo $drawBackground

    $drawBanner = NULL;
    if ($model->infocus_id && $model->banner) {
        if (is_file(str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $mediaSettings['paths']['banners']['path'] . "/" . $model->infocus_id . "." . $model->banner))) {
            $drawBanner = '<div>' . CHtml::image(Yii::app()->baseUrl . "/" . $mediaSettings['paths']['banners']['path'] . "/" . $model->infocus_id . "." . $model->banner . "?" . time(), "", array("class" => "image", "width" => "100")) . '</div>';
        }
    }

$this->widget('zii.widgets.CDetailView', array(
    'data' => $contentModel,
    'attributes' => array(
        'infocus_id',
        'header',       
        array(
            'name' => 'brief',
            'type' => 'html',
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Parent Section"),
            'value' => $model->sectionNames['parent'],
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Sub Section"),
            'value' => $model->sectionNames['sub'],
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Country"),
            'value' => ($model->country_code) ? $model->countryCode->getCountryName() : NULL,
        ),   
        array(
            'label' => AmcWm::t("msgsbase.core", "Published"),
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", "Archive"),
            'value' => ($model->archive) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'content_lang',
            'value' => ($contentModel->content_lang) ? Yii::app()->params["languages"][$contentModel->content_lang] : "",
        ),
        
//        array(
//            'label' => AmcWm::t("msgsbase.core", 'In Spot'),
//            'value' => ($model->in_spot) ? AmcWm::t("amcFront", "Yes") : AmcWm::t("amcFront", "No"),
//        ),      
        array(
            'label' => AmcWm::t("msgsbase.core", 'Image File'),
            'type' => 'html',
            'value' => ($model->thumb) ? $drawImage : AmcWm::t("amcBack", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Page Background'),
            'type' => 'html',
            'visible' => $useBackground,
            'value' => ($model->background) ? $drawBackground : AmcWm::t("amcBack", "No"),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Page Banner'),
            'type' => 'html',
            'visible' => $useBanner,
            'value' => ($model->banner) ? $drawBanner : AmcWm::t("amcBack", "No"),
        ),
          
        array(
            'label' => AmcWm::t("msgsbase.core", 'Creation Date'),
            'value'=>Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$model->create_date),
        ),
        array(            
            'label' => AmcWm::t("msgsbase.core", 'Publish Date'),
            'value'=>Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$model->publish_date),
        ),
        array(
            'label' => AmcWm::t("msgsbase.core", 'Expire Date'),
            'value'=>($model->expire_date) ? Yii::app()->dateFormatter->format("dd/MM/y hh:mm a",$model->expire_date) : NULL,
        ),                
    ),
));
?>
