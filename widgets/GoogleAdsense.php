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

class GoogleAdsense extends CWidget {

    public $ad_client = null;
    public $adSlot = null;
    public $width = null;
    public $height = null;
     /**
     * Initializes the widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        parent::init();
    }
    /**
     * Renders the goodle ad part.
     */
    public function run(){
       $output = "<!-- Google_{$this->width}x{$this->height} -->" . PHP_EOL;
        $output .= '<div align="center">' . PHP_EOL;
        $output .= '<div style="width:' . $this->width . 'px;height:' . $this->height . 'px;">' . PHP_EOL;
        $output .= '<script type="text/javascript">' . PHP_EOL;
        $output .= "    google_ad_client = '" . $this->ad_client . "' " . PHP_EOL;
        $output .= "    google_ad_slot = '" . $this->adSlot[Yii::app()->getLanguage()] . "' " . PHP_EOL;
        $output .= "    google_ad_width = " . $this->width . ";" . PHP_EOL;
        $output .= "    google_ad_height = " . $this->height . ";" . PHP_EOL;
        $output .= '</script>' . PHP_EOL;
        $output .= '<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>' . PHP_EOL;
        $output .= '</div>' . PHP_EOL;
        $output .= '</div>' . PHP_EOL;
        echo $output;
    }
}
