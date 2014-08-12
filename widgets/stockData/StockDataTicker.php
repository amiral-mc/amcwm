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
AmcWm::import('amcwm.modules.exchange.models.Exchange');

class StockDataTicker extends CWidget {

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
        $jsCode = "
            var Stock = {
                getData : function(exchangeId){
                    jQuery.ajax({
                        'url':'" . Html::createUrl("exchange/default/stock") . "',
                        'data' : {exchange_id : exchangeId},
                        'type' : 'POST',
                        'cache': false,
                        'success':function(html){
                            jQuery('#marketStocksTicker').html(html);
                            $('#stockSlider').bxSlider({'autoDelay':0,'captions':false,'controls':false,'mode':'fade','auto':true,'autoHover':true,'autoControls':false});
                        }
                    });
                }
            };
            Stock.getData($('#exchange_tradings').val());
            $('#exchange_tradings').change(function(e){
                Stock.getData($(this).val());
            });
            jQuery('#marketStocksTicker').html('<img src=\"{$this->baseScriptUrl}/images/loading.gif\" align=\"absmiddle\" /> " . AmcWm::t("amcFront", 'Loading Stock data') . "');
            
        ";
        $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
    }

    public function run() {
        $exchangeTradings = Exchange::model()->findAll(array('order' => 'exchange_name ASC'));
        $output = '<div id="exchange">
                        <div id="marketStocksSelection">
                            <ul id="borsaSwitchTop" class="borsaSwitch">
                                <li>
                                    <span>' . AmcWm::t("amcFront", 'Stock Market') . '</span>
                                </li>
                                <li class="borsa_box">'
                . CHtml::dropDownList('exchange_tradings', 'exchange_id', CHtml::listData($exchangeTradings, 'exchange_id', 'exchange_name')) .
                '</li>
                            </ul>
                        </div>
                        <div id="marketStocksTicker"></div>
                    </div>';
        echo $output;
    }

}

?>
