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

class GoogleAds extends CWidget {

    public $elementId = null;
    public $adId = null;
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
               if ($this->elementId) {
            $output .= '<div id="' . $this->elementId . '" align="center">' . PHP_EOL;
        } else {
            $output .= '<div align="center">' . PHP_EOL;
        }
       $output .= '<div id="'. $this->adId . '" style="width:'. $this->width . 'px;height:'. $this->height . 'px;">' . PHP_EOL;
       $output .= '<script type="text/javascript">' . PHP_EOL;
       $output .= "googletag.cmd.push(function() { googletag.display('".$this->adId."'); });" . PHP_EOL;
       $output .= '</script>' . PHP_EOL;
       $output .= '</div>' . PHP_EOL;
       $output .= '</div>' . PHP_EOL;
       echo $output;
    }
}
