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
class StockDataGraph extends CWidget {

    public $baseScriptUrl;
    
    public function init() {
        $assetsFolder = "";
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.stockData.assets'));
            $this->baseScriptUrl = $assetsFolder . "/stockData";
        }
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $jsCode = "";
        $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
    }

    public function run() {
        $output = '<div class="wdg_box"> 
                        <div class="wdg_box_head"><h2><strong><a href="#" title="">أسواق المال</a></strong></h2></div>
                        <div class="boxBody">
                                <div class="wdg_box_wrapper">								

                                        <div id="stock-market">

                                                <div id="stock-markets">
                                                        سوق المال: 											
                                                        <img alt="" src="images/drop-down-menu.png" height="25px" />
                                                </div>

                                                <div class="index-details">
                                                        <div class="index-date">آخر تحديث :<span>25/04/2014</span></div>
                                                        <div class="index-value-line">
                                                                <div class="index-price price-down">8,802.29</div>
                                                                <div class="index-change">
                                                                        <span class="change-label">التغير :</span>
                                                                        <span class="change-value p-down">- 20.5</span>
                                                                        <span class="change-percentage p-down">( - 0.25% )</span>
                                                                </div>
                                                        </div>

                                                        <div class="index-value-line">
                                                                <div class="index-turnover">
                                                                        <div class="index-label">القيمة المتداولة</div>
                                                                        <div>5,309,802,339.90</div>
                                                                </div>
                                                                <div class="index-volume">
                                                                        <div class="index-label">الأسهم المتداولة</div>
                                                                        <div>159,343,343</div>
                                                                </div>											
                                                        </div>

                                                        <div id="index-chart" class="index-value-line">
                                                                <div class="title">المؤشر العام</div>
                                                                <div class="index-chart"><img src="images/chart.jpg" style="max-width: 300px;" /></div>
                                                        </div>

                                                        <div id="index-companies" class="index-value-line">
                                                                <img src="images/companies_tabel.jpg" style="max-width: 300px;" />
                                                        </div>										

                                                </div>
                                        </div>

                                </div>
                        </div>
                </div>';
        echo $output;
    }

}

?>
