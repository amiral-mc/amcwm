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
class Youtube extends CWidget {

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();
    /**
     * @var boolean whether the news items should be HTML-encoded. Defaults to true.
     */
    public $encodeItem = false;
    /**
     * @var string the base script URL for all tickers resources (e.g. javascript, CSS file, images).
     */
    public $baseScriptUrl;
     /**
     * @var string the youtube url
     */
    public $url;
    /**
     * @var int widget width
     */
    public $width;
    /*
     * var boolean show or hide the progress bar
     */
    public $showProgressBar = false;

    /**
     * Initializes the scroller widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();

        $assetsFolder = "";
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.youtube.assets'));
            $this->baseScriptUrl = $assetsFolder . "/youtube";
        }
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->baseScriptUrl . '/jquery.swfobject.1-1-1.min.js');
        $cs->registerScriptFile($this->baseScriptUrl . '/youTubeEmbed-jquery-1.0.js'); 
        $cs->registerCssFile($this->baseScriptUrl . '/youTubeEmbed-jquery-1.0.css', 'screen');
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $output = "";
        $output .= CHtml::openTag('div', $this->htmlOptions) . "\n";
        $output .= CHtml::closeTag('div');
        $jsCode = "
            $('#{$this->getId()}').youTubeEmbed({
	video			: '{$this->url}',
	width			: {$this->width}, 		// Height is calculated automatically
	progressBar	: ".(($this->showProgressBar) ? 'true' : 'false')."		// Hide the progress bar
});
            ";
        $cs = Yii::app()->getClientScript();        
        $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        echo $output;
    }

}
