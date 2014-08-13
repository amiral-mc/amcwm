<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */
/**
 * NewsSideList extension class, displays the most articles list widget
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 * 
 */
AmcWm::import('amcwm.modules.exchange.models.Exchange');

class ExchangeSideWidget extends SideWidget {

    public $baseScriptUrl;

    /**
     * @var news list
     */
    public $items = array();
    private $_companies = '';

    public function init() {
        parent::init();
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.stockData.assets'));
            $this->baseScriptUrl = $assetsFolder . "/stockData";
        }
        $settings = Data::getInstance()->getSettings('exchange');
        $graphLabelsLimit = $settings->settings['frontend']['options']['graphLabelsLimit'];
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $jsCode = "
            var isJson = true;
            var StockData = {
                getData : function(exchangeId){
                    jQuery.ajax({
                        'url':'" . Html::createUrl("exchange/default/stockDetails") . "',
                        'data' : {exchange_id : exchangeId},
                        'dataType' : 'json',
                        'type' : 'POST',
                        'cache': false,
                        'success':function(data){
                            RGraph.reset(document.getElementById('exchangeRgraph'));
                            var labels = [];
                            var xsticks = " . $graphLabelsLimit . " - 1;
                            var interval = Math.ceil(data['labels'].length / xsticks);
                            var minimum = Math.floor(data['labels'].length / xsticks);
                            if(data['labels'].length < (xsticks + 1)){
                                labels = data['labels'];
                                xsticks = labels.length - 1;
                            }
                            else{
                                for(i = 0; i < (xsticks * minimum); i = i + interval){
                                    labels.push(data['labels'][i]);                            
                                }
                                labels.push(data['labels'][data['labels'].length - 1]);
                            }
                            var myChart = new RGraph.Line({
                                id: 'exchangeRgraph',
                                data: data['values'],
                                options: {
                                    tooltips: {
                                        self: function (idx) {                                                       
                                            var label = '';
                                            if(typeof data['labels'][idx] != 'undefined'){
                                                label += data['labels'][idx];
                                            }
                                            if(typeof data['values'][idx] != 'undefined'){
                                                label += ' [' + data['values'][idx] + ']';
                                            }
                                            return label;
                                        },
                                        hotspot: {
                                            xonly: true
                                        }
                                    },
                                    title: '" . AmcWm::t('msgsbase.tradings', 'General Index') . "',
                                    gutter: {
                                        left: 40,
                                        right: 30,
                                    },
                                    hmargin: 10,
                                    linewidth: 2,
                                    tickmarks: 'endcircle',
                                    numxticks: xsticks,
                                    labels: labels,
                                }
                            }).draw();
                            setData(data);
                            $('#stockSlider').bxSlider({'autoDelay':0,'captions':false,'controls':false,'mode':'fade','auto':true,'autoHover':true,'autoControls':false});
                        }
                    });
                },
                getCompanies : function(exchangeId){
                    jQuery.ajax({
                        'url':'" . Html::createUrl("exchange/default/stock") . "',
                        'data' : {exchange_id : exchangeId, is_json : isJson},
                        'dataType' : 'json',
                        'type' : 'POST',
                        'cache': false,
                        'success':function(data){
                            $('#index-companies').html(data);
                        }
                    });
                }
            };
            StockData.getData($('#stock_tradings').val());
            StockData.getCompanies($('#stock_tradings').val());
            function setData(data){
                if(data['latest'].difference_percentage > 0){
                    $('#stock-difference-percentage').removeClass('p-down');
                    $('#stock-difference-percentage').addClass('p-up');
                }
                else{
                    $('#stock-difference-percentage').removeClass('p-up');
                    $('#stock-difference-percentage').addClass('p-down');
                }
                $('#stock-difference-percentage').html('%' + data['latest'].difference_percentage);
                if(data['latest'].difference_value > 0){
                    $('#stock-difference-value').removeClass('p-down');
                    $('#stock-closing-value').removeClass('price-down');
                    $('#stock-difference-value').addClass('p-up');
                    $('#stock-closing-value').addClass('price-up');
                }
                else{
                    $('#stock-difference-value').removeClass('p-up');
                    $('#stock-closing-value').removeClass('price-up');
                    $('#stock-difference-value').addClass('p-down');
                    $('#stock-closing-value').addClass('price-down');
                }
                $('#stock-difference-value').html(data['latest'].difference_value);
                $('#stock-closing-value').html(data['latest'].closing_value);
                $('#stock-date').html(data['latest'].exchange_date);
                $('#stock-trading-value').html(data['latest'].trading_value);
                $('#stock-shares').html(data['latest'].shares_of_stock);
            }
            $('#stock_tradings').change(function(e){
                StockData.getData($(this).val());
                StockData.getCompanies($(this).val());
            });
            jQuery('#stock-data').html('<img src=\"{$this->baseScriptUrl}/images/loading.gif\" align=\"absmiddle\" /> " . AmcWm::t("amcFront", 'Loading Stock data') . "');
        ";
        $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
    }

    public function setContentData() {
        echo $this->_companies;
        
        $exchangeTradings = Exchange::model()->findAll(array('order' => 'exchange_name ASC'));
        $this->contentData = '<div id="stock-market">';
        $this->contentData .= '<div id="stock-markets">';
        $this->contentData .= 'سوق المال:';
        $this->contentData .= CHtml::dropDownList('stock_tradings', 'exchange_id', CHtml::listData($exchangeTradings, 'exchange_id', 'exchange_name'));
        $this->contentData .= '</div>';
        $this->contentData .= '<div class="index-details">';
        $this->contentData .= '<div class="index-date">' . AmcWm::t('msgsbase.companies', 'Last Update') . '<span id = "stock-date"></span></div>';
        $this->contentData .= '<div class="index-value-line">';
        $this->contentData .= '<div class="index-price" id = "stock-closing-value"></div>';
        $this->contentData .= '<div class="index-change">';
        $this->contentData .= '<span class="change-label">' . AmcWm::t('msgsbase.companies', 'Difference') . '</span>';
        $this->contentData .= '<span class="change-value" id = "stock-difference-value"></span>';
        $this->contentData .= '<span class="change-percentage" id = "stock-difference-percentage"></span>';
        $this->contentData .= '</div>';
        $this->contentData .= '</div>';
        $this->contentData .= '<div class="index-value-line">';
        $this->contentData .= '<div class="index-turnover">';
        $this->contentData .= '<div class="index-label">' . AmcWm::t('msgsbase.tradings', 'Exchange Trading Value') . '</div>';
        $this->contentData .= '<div id = "stock-trading-value"></div>';
        $this->contentData .= '</div>';
        $this->contentData .= '<div class="index-volume">';
        $this->contentData .= '<div class="index-label">' . AmcWm::t('msgsbase.tradings', 'Exchange Shares of Stock') . '</div>';
        $this->contentData .= '<div id = "stock-shares"></div>';
        $this->contentData .= '</div>';
        $this->contentData .= '</div>';
        $this->contentData .= '<div id="index-chart" class="index-value-line">';
//        $this->contentData .= '<div class="title">المؤشر العام</div>';
        $this->contentData .= $this->widget('amcwm.widgets.RGraph.RGraphLine', array(
            'id' => "exchangeRgraph",
            'allowDynamic' => true,
            'allowTooltips' => true,
        ), true);
        $this->contentData .= '</div>';
        $this->contentData .= '<div id="index-companies" class="index-value-line">';
        $this->contentData .= '</div>';
        $this->contentData .= '</div>';
        $this->contentData .='</div>';
    }

}
