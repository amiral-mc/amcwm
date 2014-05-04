<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class GallerySlider extends CWidget {

    public $baseUrl = null;
    public $thumbPostfix = '-th';
    /**
     * @var array items for the container
     */
    public $items = array();
    /**
     * the media Path
     * @var string
     */
    public $mediaPath = '';
    /**
     * the media thumb Path
     * @var string
     */
    public $mediaThumbPath = '';
    public $slideshowOptions = array();
    /**
     * the media thumb width in the container
     * @var int
     */
    public $thumbWidth = 90;
    /**
     * the media thumb height in the container     
     * @var int
     */
    public $thumbHeight = 60;
    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = "ad-gallery";
        $this->htmlOptions['style'] = 'padding: 30px;';

        $assetsFolder = "";
        if ($this->baseUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.galleryslider.assets'));
            $this->baseUrl = $assetsFolder . "/galleryslider";
        }
        parent::init();
    }

    public function run() {
        $output = "";
        if (count($this->items)) {
            $slideshowOptions['slideshow'] = $this->slideshowOptions;
            $slideshowOptions = CJSON::encode($slideshowOptions);
            $jsCode = "            
            var galleries = $('.ad-gallery').adGallery({$slideshowOptions});
//            $('#toggle-slideshow').click(
//              function() {
//                galleries[0].slideshow.toggle();
//                return false;
//              }
//            );
//            $('#toggle-description').click(
//              function() {
//                if(!galleries[0].settings.description_wrapper) {
//                  galleries[0].settings.description_wrapper = $('#descriptions');
//                } else {
//                  galleries[0].settings.description_wrapper = false;
//                }
//                return false;
//              }
//            );
        ";
            $cs = Yii::app()->getClientScript();
            $cs->registerCssFile($this->baseUrl . '/style.css');
            $cs->registerScriptFile($this->baseUrl . '/jquery.ad-gallery.pack.js');
            $cs->registerScript(__CLASS__ . $this->getId(), $jsCode,  CClientScript::POS_END);
            $cs->registerCoreScript('jquery');
            $output = '<div class="gallery-container">';
            $output .= CHtml::openTag('div', $this->htmlOptions);
            $output .= '<div class="ad-image-wrapper"></div>';
            $output .= '<div class="ad-controls"></div>';
            $output .= '<div class="ad-nav" style="direction:ltr">';
            $output .= '<div class="ad-thumbs">';
            $output .= '<ul class="ad-thumb-list">';

            foreach ($this->items as $item) {
                $output .= '<li>';
                $output .= '<a href="' . $this->mediaPath . '/' . $item['image_id'] . '.' . $item['ext'] . '">';
                $output .= '<img src="' . $this->mediaThumbPath . '/' . $item['image_id'] . $this->thumbPostfix . '.' . $item['ext'] . '" border="0"  height="' . $this->thumbHeight . '" width="' . $this->thumbWidth . '" title="' . $item['image_header'] . '" alt="' . $item['description'] . '" />';
                $output .= '</a>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= CHtml::closeTag('div');
            $output .= '</div>';
        }
        echo $output;
    }

}