<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AmcStockController
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcStockController extends FrontendController {

    public function actionStockDetails() {
        $settings = Settings::getModuleSettings('exchange');
        $thousandSeparator = $settings['frontend']['options']['thousandSeparator'];
        $floatingSeparator = $settings['frontend']['options']['floatingSeparator'];
        $data = array();
        $dates = array();
        $closingValues = array();
        $exchangeId = (int) Yii::app()->request->getParam('exchange_id');
        $stock = new StockInfoGraph($exchangeId, 1);
        $stock->generate();
        $stockData = $stock->getRow(0);
        if($stockData){
            $stockData['trading_value'] = number_format($stockData['trading_value'], 0, $floatingSeparator, $thousandSeparator);
            $stockData['closing_value'] = number_format($stockData['closing_value'], 0, $floatingSeparator, $thousandSeparator);
            $stockData['shares_of_stock'] = number_format($stockData['shares_of_stock'], 0, $floatingSeparator, $thousandSeparator);
            $stockData['difference_value'] = number_format($stockData['difference_value'], 0, $floatingSeparator, $thousandSeparator);
        }
        $exchangeData = $stock->graphData();
        //@TODO Check why RGRaph does not work with decimals in this scenario, having to use round below
        foreach ($exchangeData as $key => $value) {
            $dates[] = date("d M", strtotime($value['exchange_date']));
            $closingValues[] = round($value['closing_value']);
        }
        $dates = array_reverse($dates);
        $data['latest'] = $stockData;
        $data['labels'] = $dates;
        $data['values'] = $closingValues;
        echo json_encode($data);
        Yii::app()->end();
    }

    public function actionStock() {
        $settings = Data::getInstance()->getSettings('exchange');
        $rowLimit = $settings->settings['frontend']['options']['tickerLimit'];
        $thousandSeparator = $settings->settings['frontend']['options']['thousandSeparator'];
        $floatingSeparator = $settings->settings['frontend']['options']['floatingSeparator'];
        $companiesLimit = $settings->settings['frontend']['options']['companiesGridLimit'];
        $isJson = Yii::app()->request->getParam('is_json', 0);
        $exchangeId = (int) Yii::app()->request->getParam('exchange_id');
        $stock = new StockInfoTicker($exchangeId);
        $stock->setUseCount(false);
        $stock->generate();
        $data = $stock->getData();
        if ($isJson && $data) {
            $data = array_slice($data, 0, $companiesLimit);
            echo $this->renderPartial('companyGrid', array('data' => $data, 'floatingSeparator' => $floatingSeparator, 'thousandSeparator' => $thousandSeparator), true);
        } else {
            echo $this->renderPartial('ticker', array('data' => $data, 'rowLimit' => $rowLimit, 'floatingSeparator' => $floatingSeparator, 'thousandSeparator' => $thousandSeparator), true);
        }
        Yii::app()->end();
    }

}
