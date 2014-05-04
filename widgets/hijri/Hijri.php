<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @todo need to implement the hijri convertor  
 */

/**
 * Description of Hijri
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */

class Hijri extends CWidget {

    /**
     * class name
     * @var string 
     */
    public $className = 'hijri-wrapper';
    /**
     * @var string title displayed before printing hijri date
     */
    public $title = null;
    /**
     * @var int time stamp to generate hijri date from it
     */
    public $datetime = null;
    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();
    /**
     * @var string the base script URL for all tickers resources (e.g. javascript, CSS file, images).
     */
    public $baseScriptUrl;

    /**
     * Initializes the hijri widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = $this->className;
        if ($this->datetime === null) {
            $this->datetime = time();
        }
    }

    public function run() {
        $assetsFolder = "";
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.hijri.assets'));
            $this->baseScriptUrl = $assetsFolder . "/hijri";
        }
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->baseScriptUrl . '/hijri.js');        
        $jsCode = "$('#{$this->getId()}_data').html(Hijri.writeIslamicDate(false, ". CJSON::encode(Yii::t("hijri", "jsHijri")).", '". Yii::t("hijri", "AH")."'));";
        $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        $output = CHtml::openTag('span', $this->htmlOptions) . "\n";
        if ($this->title !== null) {
            //$output .= $this->title . "&nbsp;";
        }
        $output .= '<span id="'.$this->getId().'_data"></span>';
        $output .= CHtml::closeTag('span') . "\n";
        echo $output;
        
    }

}