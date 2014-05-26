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
    /*
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

    /*
     * Hidden field name storing cropping information in a JSON object
     */
    public $hiddenField = 'coords';

    /**
     * Initializes the widget.
     * properties have been initialized.
     */
    public function init() {
        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
        $cl = Yii::app()->getClientScript();
        $cl->registerScriptFile($assets . '/js/jquery.Jcrop.min.js');
        $cl->registerCssFile($assets . '/css/jquery.Jcrop.min.css');
        //$cl->registerScriptFile($assets . '/js/jquery.color.js');
        $cl->registerScriptFile($assets . '/js/jcrop.js', CClientScript::POS_HEAD);
        parent::init();
    }

    /**
     * Executes the widget.
     * This method is called by {@link CBaseController::endWidget}.
     */
    public function run() {
        list($name, $uploaderId) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $uploaderId = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $uploaderId;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];
        else
            $this->htmlOptions['name'] = $name;

        if ($this->hasModel()){            
            echo CHtml::activeFileField($this->model, $this->attribute, $this->htmlOptions);
        }
        else{
            echo CHtml::fileField($name, $this->value, $this->htmlOptions);
        }
        echo '<input id="coords" name="coords" type="hidden" />';
        $yesIcon = AmcWm::app()->baseUrl . '/images/yes.png';
        $noIcon = AmcWm::app()->baseUrl . '/images/no.png';
        $widthSizes = array();
        $heightSizes = array();
        $sizes = array();
        $jsCrop = 'function(){'
                . '$("#' . $this->hiddenField . '").val(JSON.stringify(imageCropper.data.currentCoords)); '
                . 'var cropWidth = parseInt(imageCropper.data.currentCoords.x2 - imageCropper.data.currentCoords.x);'
                . 'var cropHeight= parseInt(imageCropper.data.currentCoords.y2 - imageCropper.data.currentCoords.y);'
                . 'var thumbWidth= imgWidth * imageCropper.data.ratio;' // Get original image width
                . 'var thumbHeight= imgHeight * imageCropper.data.ratio;' // Get original image height
                . 'var ratio = 1;'
                . 'if(cropWidth > ' . $this->thumbnailInfo['width'] . '){'
                . 'ratio = parseInt(cropWidth / ' . $this->thumbnailInfo['width'] . ');'
                . 'cropWidth   = ' . $this->thumbnailInfo['width'] . ';'
                . 'cropHeight  = parseInt(cropHeight  / ratio);'
                . 'thumbWidth  = parseInt(thumbWidth  / ratio);' // Resize original image width
                . 'thumbHeight = parseInt(thumbHeight / ratio);' // Resize original image height
                . '}'
                . '$("#dialog_' . $uploaderId . '").dialog("close");'
                . '$("#thumb_prev_' . $uploaderId . '").html("");'
                . '$("#thumb_prev_' . $uploaderId . '").width(cropWidth);'
                . '$("#thumb_prev_' . $uploaderId . '").height(cropHeight);'
                . '$("#thumb_prev_' . $uploaderId . '").css({'
                . '"background" : "url(" + imgSrc + ") no-repeat", '
                . '"background-size" : thumbWidth + "px " + thumbHeight + "px", '
                . '"background-position" : "-" + parseInt(imageCropper.data.currentCoords.x / ratio) + "px -" + parseInt(imageCropper.data.currentCoords.y / ratio) + "px", ' // Get cropped area coords from original image size and divide by ratio.
                . '});'
                . '}';
        if ($this->thumbnailSrc) {
            echo '<div id="thumb_prev_' . $uploaderId . '"><img src="' . $this->thumbnailSrc . '"/></div>';
        } else {
            echo '<div id="thumb_prev_' . $uploaderId . '"></div>';
        }
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => "dialog_{$uploaderId}",
            'options' => array(
                'title' => 'Image Cropper',
                'autoOpen' => false,
                'modal' => true,
                'buttons' => array(
                    AmcWm::t("amcBack", 'Crop') => 'js:' . $jsCrop,
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
        foreach ($this->sizesInfo as $key => $value) {
            if (isset($value['autoSave']) && $value['autoSave']) {
                $widthSizes[] = $value['info']['width'];
                $heightSizes[] = $value['info']['height'];
                $sizes[$key]['width'] = $value['info']['width'];
                $sizes[$key]['height'] = $value['info']['height'];
                echo '<div><img id="icon_size_' . $key . '" src="' . $noIcon . '"><span class="icon_size_label">' . $value['info']['width'] . ' x ' . $value['info']['height'] . '</span></div>';
            }
        }
        echo '</div>';
        $this->endWidget('zii.widgets.jui.CJuiDialog');

        $this->options['onSelect'] = 'js:showCoords';
        $this->options['setSelect'] = array(100, 100, 50, 50);
        $this->options['aspectRatio'] = 16 / 9;
        $this->options['minSize'] = array(min($widthSizes), min($heightSizes));
        $this->options['maxSize'] = array(max($widthSizes), max($heightSizes));
        $options = CJavaScript::encode($this->options);
        Yii::app()->clientScript->registerScript('cropping', "
                var imgSrc;
                var imgWidth;
                var imgHeight;
                var imageCropper = {
                    data : {
                        currentCoords : {},
                        ratio : 1,
                    },
                    sizes: " . CJavaScript::encode($sizes) . "
                };
                
                $('#{$uploaderId}').change(function(e){
                        var files = this.files;
                        if (this.files[0].type.match(/image/)) {
                            (function(file){                    
                                var reader = new FileReader();
                                reader.onload = function(e){
                                    $('#container_{$uploaderId}').html('');
                                    var myImg = new Image();    
                                    var maxImageSize = 800 - 25;
                                    myImg.src = imgSrc = e.target.result;
                                    imageCropper.data.ratio = 1;
                                    if(myImg.width > maxImageSize){
                                        imageCropper.data.ratio = myImg.width / maxImageSize;
                                        myImg.width = maxImageSize;
                                        myImg.height = myImg.height / imageCropper.data.ratio;
                                    }
                                    imgWidth = myImg.width;
                                    imgHeight = myImg.height;
                                    $('#dialog_{$uploaderId}').dialog('open');
                                    $('#container_{$uploaderId}').append(myImg);
                                    $(myImg).Jcrop({$options});

                                };
                                reader.readAsDataURL(file);
                            })(this.files[0])
                    
                        }
                        return false;
                        
                });          
                function showCoords(c) {
                    imageCropper.data.currentCoords.x = c.x
                    imageCropper.data.currentCoords.x2 = c.x2
                    imageCropper.data.currentCoords.y = c.y
                    imageCropper.data.currentCoords.y2 = c.y2
                    imageCropper.data.currentCoords.x *= imageCropper.data.ratio;
                    imageCropper.data.currentCoords.x2 *= imageCropper.data.ratio;
                    imageCropper.data.currentCoords.y *= imageCropper.data.ratio;
                    imageCropper.data.currentCoords.y2 *= imageCropper.data.ratio;
                    
                    var cropWidth = imageCropper.data.currentCoords.x2 - imageCropper.data.currentCoords.x;
                    var cropHeight = imageCropper.data.currentCoords.y2 - imageCropper.data.currentCoords.y;
                    $.each(imageCropper.sizes, checkSize);

                    function checkSize(key, value) {
                        if(value.width < cropWidth){
                          $('#icon_size_' + key).attr('src', '$yesIcon');
                        }
                        else{
                          $('#icon_size_' + key).attr('src', '$noIcon');
                        }
                        if (value !== null && typeof value === 'object') {
                            $.each(value, checkSize);
                        }
                    }
                }
            "
                , CClientScript::POS_READY);
    }

}
