<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of ZeroClipboard
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ZeroClipboard extends Widget{

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();
    
    /**
     * @var string the base script URL for all tickers resources (e.g. javascript, CSS file, images).
     */
    public $baseScriptUrl;
    
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        if(!isset($this->htmlOptions['targetId']))
            $this->htmlOptions['targetId'] = $this->getId() . 'Trgt';
        
        if(!isset($this->htmlOptions['defaultTxt']))
            $this->htmlOptions['defaultTxt'] = '';
        
        if(!isset($this->htmlOptions['title']))
            $this->htmlOptions['title'] = '';
        
        if ($this->baseScriptUrl === null) {
            $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.zeroClipboard.assets'));
        }
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->baseScriptUrl . '/ZeroClipboard.min.js');
        $cs->registerScript(__CLASS__ . '_' . $this->htmlOptions['id'], '
                var clip = new ZeroClipboard( document.getElementById("'.$this->htmlOptions['id'].'"), {
                    moviePath: "'.$this->baseScriptUrl.'/ZeroClipboard.swf"
                });
                clip.on( "load", function(client) {
                    // alert( "movie is loaded" );
                });
        ', CClientScript::POS_READY);
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $output = "<button 
                        id='{$this->htmlOptions['id']}' 
                        data-clipboard-target='{$this->htmlOptions['targetId']}'
                        data-clipboard-text='{$this->htmlOptions['defaultTxt']}' 
                        title='{$this->htmlOptions['title']}'>
            {$this->htmlOptions['title']}
            </button>";
        echo $output;
    }

}

?>
