<?php

Yii::import('zii.widgets.jui.CJuiInputWidget');
/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Image Uploder , upload image and crop it
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ImageUploader extends CJuiInputWidget {

    /**
     * The sizes of images in different view modes, e.g. list view, grid view, etc...
     */
    public $sizesInfo = array();

    /*
     * @var string Stores image src path for preview
     */
    public $thumbnailSrc;

    /**
     * @var array thumbnail information of width & height
     */
    public $thumbnailInfo = array();

    /**
     *
     * @var boolean add delete icon if thumbnailSrc is not empty
     */
    protected $deleteIcon = false;
    
    /**
     *
     * @var boolean , if true then crop must fit all sizes info  
     */
    protected $cropAllSizes = true;
    
      /**
     *
     * @var boolean , Display the watermark checkbox options if equal true 
     */
    protected $watermark = false;

    /**
     * Initializes the widget.
     * This method will publish JUI assets if necessary.
     * It will also register jquery and JUI JavaScript files and the theme CSS file.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init() {
        if ($this->thumbnailSrc) {
            $this->deleteIcon = true;
        }
        if(isset(AmcWm::app()->params['cropAllSizes'])){
            $this->cropAllSizes = AmcWm::app()->params['cropAllSizes'];    
        }
        
        if(isset(AmcWm::app()->params['watermark']['image']) || isset(AmcWm::app()->params['watermark']['text'])){
            $this->watermark = true;    
        }
        parent::init();
    }

    /**
     * Executes the widget.
     * This method is called by {@link CBaseController::endWidget}.
     */
    public function run() {

        //$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, true);
        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
        $cl = Yii::app()->getClientScript();
        $cl->registerScriptFile($assets . '/js/jquery.Jcrop.min.js');
        $cl->registerCssFile($assets . '/css/jquery.Jcrop.min.css');
        //$cl->registerScriptFile($assets . '/js/jquery.color.js');
        $cl->registerScriptFile($assets . '/js/uploaderCropper.js', CClientScript::POS_END);


        list($name, $uploaderId) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $uploaderId = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $uploaderId;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];
        else
            $this->htmlOptions['name'] = $name;

        $watermarkInput = '';
        if ($this->hasModel()) {
            echo CHtml::activeFileField($this->model, $this->attribute, $this->htmlOptions);
            echo '<input id="' . $uploaderId . '_coords" name="' . $this->model->getClassName() . '[' . $this->attribute . '_coords]" type="hidden" />';
            echo '<input id="' . $uploaderId . '_deleteImage" name="' . $this->model->getClassName() . '[' . $this->attribute . '_deleteImage]" type="hidden" />';
            if($this->watermark){
                $watermarkInput = '<input id="' . $uploaderId . '_watermark" name="' . $this->model->getClassName() . '[' . $this->attribute . '_watermark]" type="checkbox" />' . AmcWm::t("amcBack", 'Use watermark');
            }
            //<input type="checkbox" name="deleteImage" id="deleteImage" style="float: right" onclick="deleteRelatedImage(this);" />
        } else {
            echo CHtml::fileField($name, $this->value, $this->htmlOptions);
            echo '<input id="' . $uploaderId . '_coords" name="' . $name . '_coords]" type="hidden" />';
            echo '<input id="' . $uploaderId . '_deleteImage" name="' . $name . '_deleteImage]" type="hidden" />';
            if($this->watermark){
                $watermarkInput .='<input id="' . $uploaderId . '_watermark" name="' . $name . '_watermark]" type="checkbox" />'. AmcWm::t("amcBack", 'Use watermark');
            }
        }
        echo $watermarkInput;
        $yesIcon = $assets . '/images/yes.png';
        $noIcon = $assets . '/images/no.png';
        $removeIcon = $assets . '/images/remove.png';
        $undoIcon = $assets . '/images/undo.png';
        $widthSizes = array();
        $heightSizes = array();
        $sizes = array();
        $iconsBar = '';
        foreach ($this->sizesInfo as $key => $value) {
            if (isset($value['autoSave']) && $value['autoSave']) {
                $widthSizes[] = $value['info']['width'];
                $heightSizes[] = $value['info']['height'];
                $sizes[$key]['width'] = $value['info']['width'];
                $sizes[$key]['height'] = $value['info']['height'];
                $iconsBar .= '<div><img id="icon_size_' . $key . '" src="' . $noIcon . '"><span class="icon_size_label">' . $value['info']['width'] . ' x ' . $value['info']['height'] . '</span></div>';
            }
        }
        $iconsBar .= '<div id="icon_size_info"></div>';
        $this->options['setSelect'] = array(0, 0, max($widthSizes), max($heightSizes));
//        $this->options['aspectRatio'] = 16 / 9;
        $this->options['minSize'] = array(min($widthSizes), min($heightSizes));
        $this->options['maxSize'] = array(max($widthSizes), max($heightSizes));
        $allOptions['cropOptions'] = $this->options;
        
        $allOptions['sizes'] = $sizes;
        $allOptions['cropAllSizesMsg'] = AmcWm::t("amcBack", 'Some photo sizes cannot be generated, please select another photo');
        $allOptions['removeIcon'] = $removeIcon;
        $allOptions['undoIcon'] = $undoIcon;
        $allOptions['yesIcon'] = $yesIcon;
        $allOptions['noIcon'] = $noIcon;
        $allOptions['undoLabel'] = AmcWm::t("amcBack", 'undo delete image');
        $allOptions['removeLabel'] = AmcWm::t("amcBack", 'Delete Image');
        $allOptions['cropAllSizes'] = $this->cropAllSizes;

        $allOptions['thumbnailInfo'] = $this->thumbnailInfo;
        $options = CJavaScript::encode($allOptions);
        Yii::app()->clientScript->registerScript('cropping', "var cropper{$uploaderId} = $('#{$uploaderId}').uploaderCropper({$options});", CClientScript::POS_READY);        
        if ($this->thumbnailSrc) {
            echo '<div id="thumb_prev_' . $uploaderId . '"><img src="' . $this->thumbnailSrc . '"/></div>';
        } else {
            echo '<div id="thumb_prev_' . $uploaderId . '"></div>';
        }
        if ($this->deleteIcon) {
            echo '<label style="cursor: pointer;" id="remove_image_' . $uploaderId . '"><img id="remove_image_icon_' . $uploaderId . '" src="' . $removeIcon . '" style="vertical-align: middle;" /><span id="remove_image_label_' . $uploaderId . '">' . $allOptions['removeLabel'] . '</span></label>';
        }
        
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => "dialog_{$uploaderId}",
            'options' => array(
                'title' => 'Image Cropper',
                'autoOpen' => false,
                'modal' => true,
                'buttons' => array(
                    'myCrop' => array(
                        'text' => AmcWm::t("amcBack", 'Crop'),
                        'id' => "dialog-{$uploaderId}-crop",
                        'click' => "js: function(){}",
                    ),
                    AmcWm::t("amcBack", 'Cancel') => 'js:function(){ $(this).dialog("close");}',
                ),
//                        'width' => 'js:function(){return imgWidth;}',
//                        'height' => 'js:function(){return imgHeight;}',
                'width' => 800,
                'height' => 600,
            ),
            'htmlOptions' => array('class' => 'dialogBoxs',)
        ));
        echo '<div id="container_' . $uploaderId . '"></div>';
        echo '<div id="container_sizes' . $uploaderId . '" dir="ltr">';
        echo $iconsBar;
        echo '</div>';
        $this->endWidget('zii.widgets.jui.CJuiDialog');
    }

}
