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
class StockData extends CWidget {
    
    public $baseScriptUrl;
    
    public function init() {
        $assetsFolder = "";
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.stockData.assets'));
            $this->baseScriptUrl = $assetsFolder . "/stockData";
        }
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->baseScriptUrl . '/jquery.bxSlider.min.js');
        //$cs->registerScriptFile($this->baseScriptUrl . '/jquery.easing.js');
        $jsCode = "
            jQuery('#marketStocksTicker').html('<img src=\"{$this->baseScriptUrl}/images/loading.gif\" align=\"absmiddle\" /> ".AmcWm::t("amcFront",'Loading Stock data')."');
            jQuery.ajax({
                'url':'".Html::createUrl("site/stockData")."',
                'cache':false,
                'success':function(html){
//                    jQuery('#marketStocksTicker').fadeOut('slow', function(){
                        jQuery('#marketStocksTicker').html(html);
//                        jQuery('#marketStocksTicker').fadeIn('slow');
                       $('#stockSlider').bxSlider({'autoDelay':0,'captions':false,'controls':false,'mode':'fade','auto':true,'autoHover':true,'autoControls':false});
//                    });
                }
            });
            
            
        ";        
        $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
    }
    
    public function run() {
        $output = '<div id="exchange">		   	
                        <div id="marketStocksSelection">
                            <ul id="borsaSwitchTop" class="borsaSwitch">
                                <li>
                                    <span>'.AmcWm::t("amcFront",'Stock Market').'</span>
                                </li>
                                <li class="borsa_box">
                                    <div class="borsaArrow"></div>
                                    <div class="currentBorsaTitle">'.AmcWm::t("amcFront",'Dubai').'</div>
                                    <div class="currentBorsa"></div>										
                                </li>
                            </ul>
                        </div>
                        <div id="marketStocksTicker"></div>
                    </div>';
        
        echo $output;
    }
}

?>
