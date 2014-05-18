<?php

/**
 * Jcrop Yii extension
 * Select a cropping area from an image using the Jcrop jQuery tool.
 * 
 * @author Abdallah El-Gammal <elgammalabdalla@gmail.com>
 * @copyright (c) 2014, Amiral Management Corporation
 */
Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * Base class.
 */
class Jcrop extends CJuiWidget {

    /**
     * @var string URL of the picture to crop.
     */
    public $url;

    /*
     * The sizes of images in different view modes, e.g. list view, grid view, etc...
     */
    public $sizesInfo = array();

    /**
     * @var type Alternate text for the full size image image.
     */
    public $alt;

    /**
     * @var array to set buttons options
     */
    public $buttons = array();

    /**
     * @var string URL for the AJAX request
     */
    public $ajaxUrl;

    /**
     * @var array Extra parameters to send with the AJAX request.
     */
    public $ajaxParams = array();

    /*
     * @var string Stores image src path for preview
     */
    public $thumbnailSrc;
    
    /**     
     * @var array thumbnail information of width & height
     */
    public $thumbnailInfo = array();

    /*
     * Hidden field storing cropping information in a JSON object
     */
    public $hiddenField;



    public function init() {
        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');

        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
        $this->id = $id = $this->htmlOptions['id'];

        $cls = Yii::app()->getClientScript();
        $cls->registerScriptFile($assets . '/js/jquery.Jcrop.min.js');
        $cls->registerScriptFile($assets . '/js/jquery.color.js');
        $cls->registerScriptFile($assets . '/js/jcrop.js', CClientScript::POS_HEAD);
        //$cls->registerCssFile($assets . '/css/jquery.Jcrop.css');
        $cls->registerCssFile($assets . '/css/jquery.Jcrop.min.css');

        $this->options['ajaxUrl'] = $this->ajaxUrl;
        $this->options['ajaxParams'] = $this->ajaxParams;

        $options = CJavaScript::encode($this->options);      
    }

    public function run() {
        $yesIcon = AmcWm::app()->baseUrl . '/images/yes.png';
        $noIcon = AmcWm::app()->baseUrl . '/images/no.png';
        $widthSizes = array();
        $heightSizes = array();
        $sizes = array();        
        $jsCrop = 'function(){'
                    . '$("#' . $this->hiddenField . '").val(JSON.stringify(imageCropper.data.currentCoords)); '
                    . 'var cropWidth = parseInt(imageCropper.data.currentCoords.x2 - imageCropper.data.currentCoords.x);'
                    . 'var cropHeight= parseInt(imageCropper.data.currentCoords.y2 - imageCropper.data.currentCoords.y);'
                    . 'var thumbWidth= imgWidth * imageCropper.data.ratio;'
                    . 'var thumbHeight= imgHeight * imageCropper.data.ratio;'
                    . 'var ratio = 1;'
                    . 'if(cropWidth > ' . $this->thumbnailInfo['width'] . '){'
                        . 'ratio = parseInt(cropWidth / ' . $this->thumbnailInfo['width'] . ');'
                        . 'cropWidth = ' . $this->thumbnailInfo['width'] . ';'
                        . 'cropHeight = parseInt(cropHeight / ratio);'
                        . 'thumbWidth = parseInt(thumbWidth / ratio);'
                        . 'thumbHeight = parseInt(thumbHeight / ratio);'
                    . '}'                
                    . '$("#' . $this->id . '").dialog("close");'
                    . '$("#thumb_prev_' . $this->id . '").html("");'
                    . '$("#thumb_prev_' . $this->id . '").width(cropWidth);'
                    . '$("#thumb_prev_' . $this->id . '").height(cropHeight);'
                    . '$("#thumb_prev_' . $this->id . '").css({'
                        . '"background" : "url(" + imgSrc + ") no-repeat", '
                        . '"background-size" : thumbWidth + "px " + thumbHeight + "px", '
                        . '"background-position" : "-" + parseInt(imageCropper.data.currentCoords.x / ratio) + "px -" + parseInt(imageCropper.data.currentCoords.y / ratio) + "px", '
                    . '});'
                . '}';
        if($this->thumbnailSrc){
            echo '<div id="thumb_prev_' . $this->id . '"><img src="' . $this->thumbnailSrc .'"/></div>';
        }
        else{
            echo '<div id="thumb_prev_' . $this->id . '"></div>';
        }
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => $this->id,
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
        echo '<div id="container_' . $this->id . '"></div>';
        echo '<div id="container_sizes' . $this->id . '" dir="ltr">';
        foreach ($this->sizesInfo as $key => $value) {
            if(isset($value['autoSave']) && $value['autoSave']){
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
                var file_selector = document.getElementById('{$this->url}');
                file_selector.addEventListener('change', function(e) {
                    var files_array = this.files;
                    if (files_array[0].type.match(/image/)) {
                        read_image_file(files_array[0]);
                    }
                }, false);

                function read_image_file(file) {
                    var reader = new FileReader();
                    reader.onload = function(e){
                        $('#container_{$this->id}').html('');
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
                        $('#{$this->id}').dialog('open');
                        $('#container_{$this->id}').append(myImg);
                        $(myImg).Jcrop({$options});

                    };
                    reader.readAsDataURL(file);
                }

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