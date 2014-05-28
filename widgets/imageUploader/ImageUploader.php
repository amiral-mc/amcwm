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

    /**
     * Executes the widget.
     * This method is called by {@link CBaseController::endWidget}.
     */
    public function run() {

        $assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, true);
        $cl = Yii::app()->getClientScript();
        $cl->registerScriptFile($assets . '/js/jquery.Jcrop.min.js');
        $cl->registerCssFile($assets . '/css/jquery.Jcrop.min.css');
        //$cl->registerScriptFile($assets . '/js/jquery.color.js');
        $cl->registerScriptFile($assets . '/js/jcrop.js', CClientScript::POS_HEAD);
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

        if ($this->hasModel()) {
            echo CHtml::activeFileField($this->model, $this->attribute, $this->htmlOptions);
            echo '<input id="' . $uploaderId . '_coords" name="' . $this->model->getClassName() . '[' . $this->attribute . '_coords]" type="hidden" />';
        } else {
            echo CHtml::fileField($name, $this->value, $this->htmlOptions);
            echo '<input id="' . $uploaderId . '_coords" name="' . $name . '_coords]" type="hidden" />';
        }
        $yesIcon = $assets . '/images/yes.png';
        $noIcon = $assets . '/images/no.png';
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

        $this->options['setSelect'] = array(100, 100, 50, 50);
        $this->options['aspectRatio'] = 16 / 9;
        $this->options['minSize'] = array(min($widthSizes), min($heightSizes));
        $this->options['maxSize'] = array(max($widthSizes), max($heightSizes));        
        $allOptions['cropOptions'] = $this->options;
        
        $allOptions['sizes'] = $sizes;
        $allOptions['yesIcon'] = $yesIcon;
        $allOptions['noIcon'] = $noIcon;
        $allOptions['thumbnailInfo'] = $this->thumbnailInfo;
        $options = CJavaScript::encode($allOptions);   
        Yii::app()->clientScript->registerScript('cropping', "var cropper{$uploaderId} = $('#{$uploaderId}').uploaderCropper({$options});", CClientScript::POS_READY);
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
                    AmcWm::t("amcBack", 'Crop') => "js: function(){
                            cropper{$uploaderId}.crop();
                    }",
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
