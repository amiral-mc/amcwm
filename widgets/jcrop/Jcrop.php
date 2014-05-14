<?php

/**
 * Modified by Abdallah El-Gammal <elgammalabdalla@gmail.com>
 * Amiral Management Corporation
 * In development progress (NOT FINAL)...
 * 
 * Jcrop Yii extension
 * 
 * Select a cropping area fro an image using the Jcrop jQuery tool and crop
 * it using PHP's GD functions.
 *
 * @copyright © Digitick <www.digitick.net> 2011
 * @license GNU Lesser General Public License v3.0
 * @author Jacques Basseck
 * @author Ianaré Sévi
 * 
 */
Yii::import('zii.widgets.jui.CJuiWidget');

/**
 * Base class.
 */
class Jcrop extends CJuiWidget
{
	/**
	 * @var string URL of the picture to crop.
	 */
	public $url;
        
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
         * @var string HTML id where image will be wrapped inside
         */
        public $container;
        
        /*
         * Hidden field that stores the JSON object
         */
        public $hiddenField;
        
        public $width;
        
        public $height;

	public function init()
	{
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');

		if (!isset($this->htmlOptions['id'])) {
			$this->htmlOptions['id'] = $this->getId();
		}
		$this->id = $id = $this->htmlOptions['id'];
                
		//echo CHtml::image($this->url, $this->alt, $this->htmlOptions);
                
//		if (!empty($this->buttons)) {
//			echo '<div class="jcrop-buttons">' .
//			CHtml::button($this->buttons['start']['label'], $this->getHtmlOptions('start', 'inline'));
//			echo CHtml::button($this->buttons['crop']['label'], $this->getHtmlOptions('crop'));
//			echo CHtml::button($this->buttons['cancel']['label'], $this->getHtmlOptions('cancel')) .
//			'</div>';
//		}
//		echo CHtml::hiddenField($this->url . '_x', 0, array('class' => 'coords'));
//		echo CHtml::hiddenField($this->url . '_y', 0, array('class' => 'coords'));
//		echo CHtml::hiddenField($this->url . '_w', 0, array('class' => 'coords'));
//		echo CHtml::hiddenField($this->url . '_h', 0, array('class' => 'coords'));
//		echo CHtml::hiddenField($this->url . '_x2', 0, array('class' => 'coords'));
//		echo CHtml::hiddenField($this->url . '_y2', 0, array('class' => 'coords'));

                
                
                
		$cls = Yii::app()->getClientScript();
		$cls->registerScriptFile($assets . '/js/jquery.Jcrop.min.js');
                $cls->registerScriptFile($assets . '/js/jquery.color.js');
		$cls->registerScriptFile($assets . '/js/jcrop.js', CClientScript::POS_HEAD);
		//$cls->registerCssFile($assets . '/css/jquery.Jcrop.css');
		$cls->registerCssFile($assets . '/css/jquery.Jcrop.min.css');
		

//		$this->options['onChange'] = "js:function(c) {"
//                        . "jcrop_getCoords(c,'{$id}'); "
//                        . "jcrop_showThumb(c,'{$id}', '{$this->container}');"
//                        . "}";
		$this->options['ajaxUrl'] = $this->ajaxUrl;
		$this->options['ajaxParams'] = $this->ajaxParams;

		$options = CJavaScript::encode($this->options);
                //print_r($this->options); exit;               
//               echo '<div id="container_' . $this->id .'"></div>';
//		if (!empty($this->buttons)) {
//			$js = "jcrop_initWithButtons('{$id}', {$options}, '{$this->container}');";
//		}
//		else {
//			$js = "jQuery('#image_{$id}').Jcrop({$options});";
//		}
//                
//		$cls->registerScript(__CLASS__ . '#' . $id, $js, CClientScript::POS_READY);
                
	}
        
        public function run(){
//            print_r($this->sizesInfo); exit;
            $widthSizes = array();
            $heightSizes = array();
            $sizes = array();
            $jsCrop = 'function(){$("#' . $this->hiddenField . '").val(JSON.stringify(imageCropper.data.currentCoords)); $("#' . $this->id . '").dialog("close");}';
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
            echo '<div id="container_' . $this->id .'"></div>';
            echo '<div id="container_sizes' . $this->id .'" dir="ltr">';
            foreach($this->sizesInfo as $key => $value){
                $widthSizes[] = $value['info']['width'];
                $heightSizes[] = $value['info']['height'];
                $sizes[$key]['width'] = $value['info']['width'];
                $sizes[$key]['height'] = $value['info']['height'];
                echo '<div><img id="icon_size_' . $key .'" src="' . AmcWm::app()->baseUrl.'/images/no.png"><span class="icon_size_label">' . $value['info']['width'] . 'X' . $value['info']['height'] . '</span></div>';
            }
            echo '</div>';
//            echo '<img id="icon_' . $this->id .'" src=""></div>';
            // draw info bar
            $this->endWidget('zii.widgets.jui.CJuiDialog');
            
            $this->options['onSelect'] = 'js:showCoords';
            $this->options['setSelect'] = array(100, 100, 50, 50);
            $this->options['aspectRatio'] = 16 / 9;
            $this->options['minSize'] = array(min($widthSizes), min($heightSizes));
            // add min , max from sizesInfo;
            $options = CJavaScript::encode($this->options);
            Yii::app()->clientScript->registerScript('cropping', "
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
                    if (files_array[0].type.match(/image/)) { // it's an image file
                        read_image_file(files_array[0]);
                    }
                }, false);

                function read_image_file(file) {
                    var reader = new FileReader();
                    reader.onload = function(e){
                        $('#container_{$this->id}').html('');
                        var myImg = new Image();    
                        var maxImageSize = 800 - 25;
                        myImg.src = e.target.result;
                        if(myImg.width > maxImageSize){
                            imageCropper.data.ratio = myImg.width / maxImageSize;
                            myImg.width = maxImageSize;
                            myImg.height = myImg.height / imageCropper.data.ratio;
                        }
                        imgWidth = myImg.width;
                        imgHeight = myImg.height;
                        console.log(imgHeight);
                        $('#{$this->id}').dialog('open');
                        $('#container_{$this->id}').append(myImg);                        
                        //$('#{$this->container}').append(myImg);
                        $(myImg).Jcrop({$options});

                    };
                    reader.readAsDataURL(file);
                }

                function sizesValidation(c){
                    imageCropper.sizes;
                    
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
                    //console.log(imageCropper.data.currentCoords);
                    if($('#{$this->hiddenField}').length){
                        //$('#{$this->hiddenField}').val(JSON.stringify(imageCropper.data));
                        //console.log($('#{$this->hiddenField}').val());
                    }
                   
                }
            "
                        , CClientScript::POS_READY);
        }
        
        

	/**
	 * Get the HTML options for the buttons.
	 * 
	 * @param string $name button name
	 * @return array HTML options 
	 */
	protected function getHtmlOptions($name, $display='none')
	{
		if (isset($this->buttons[$name]['htmlOptions'])) {
			if (isset($this->buttons[$name]['htmlOptions']['id'])) {
				throw new CException("id for jcrop '{$name}' button may not be set manually.");
			}
			$options = $this->buttons[$name]['htmlOptions'];

			if (isset($options['class'])) {
				$options['class'] = $options['class'] . " jcrop-{$name}";
			}
			else {
				$options['class'] = "jcrop-{$name}";
			}
			if (isset($options['style'])) {
				$options['style'] = $options['style'] . " display:{$display};";
			}
			else {
				$options['style'] = "display:{$display};";
			}
			$options['id'] = $name . '_' . $this->id;
		}
		else {
			$options = array('id' => $name . '_' . $this->id, 'style' => "display:{$display};", 'class' => "jcrop-{$name}");
		}
		return $options;
	}

}
