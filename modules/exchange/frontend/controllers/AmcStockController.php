<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StockInfoController
 *
 * @author Abdallah
 */
class AmcStockController extends FrontendController {

    public function actionStockDetails() {
        $settings = Data::getInstance()->getSettings('exchange');
        $graphDaysLimit = $settings->settings['frontend']['options']['graphDaysLimit'];
        $data = array();
        $dates = array();
        $closingValues = array();
        $exchangeId = (int) Yii::app()->request->getParam('exchange_id');
        $stock = new StockInfoGraph($exchangeId);
        $stock->generate();
        $stockData = $stock->getRow(0);
        
        $exchangeData = Yii::app()->db->createCommand()
                ->select('exchange_date, closing_value')
                ->from('exchange_trading')
                ->where('exchange_id =' . $exchangeId)
                ->order('exchange_date ASC')
                ->limit($graphDaysLimit)
                ->queryAll();
        //@TODO Check why RGRaph does not work with decimals in this scenario, having to use round below
        foreach ($exchangeData as $key => $value) {
            $dates[] = $value['exchange_date'];
            $closingValues[] = round($value['closing_value']);
        }
        $data['latest'] = $stockData;
        $data['labels'] = $dates;
        $data['values'] = $closingValues;
        echo json_encode($data);
        Yii::app()->end();
    }

    public function actionStock() {
        $settings = Data::getInstance()->getSettings('exchange');
        $rowLimit = $settings->settings['frontend']['options']['tickerLimit'];
        $companiesLimit = $settings->settings['frontend']['options']['companiesGridLimit'];
        $isJson = Yii::app()->request->getParam('is_json', 0);
        $exchangeId = (int) Yii::app()->request->getParam('exchange_id');
        $stock = new StockInfoTicker($exchangeId, $companiesLimit);
        $stock->generate();
        $data = $stock->getData();
        $output = "";
        if ($isJson && $data) {
            $output .= "<table>";
            $output .= "<tr>";
            $output .= "<td class ='header'>" . AmcWm::t('msgsbase.companies', 'Company Name') . "</td>";
            $output .= "<td class ='header'>" . AmcWm::t('msgsbase.companies', 'Opening Value') . "</td>";
            $output .= "<td class ='header'>" . AmcWm::t('msgsbase.companies', 'Closing Value') . "</td>";
            $output .= "<td class ='header'>" . AmcWm::t('msgsbase.companies', 'Difference %') . "</td>";
            $output .= "</tr>";
            foreach ($data as $key => $value) {
                if ($key % 2 == 0) {
                    $class = "even";
                } else {
                    $class = "odd";
                }
                $output .= "<tr class =" . $class . ">";
                $output .= "<td>" . $value['company_name'] . "</td>";
                $output .= "<td>" . $value['opening_value'] . "</td>";
                $output .= "<td>" . $value['closing_value'] . "</td>";
                $output .= "<td>" . $value['difference_percentage'] . "</td>";
                $output .= "</tr>";
            }
            $output .= "</table>";
            echo json_encode($output);
        } else {
            $eachCols = ceil(count($data) / $rowLimit);
            if ($eachCols) {
                $output .= CHtml::openTag("ul", array("id" => "stockSlider", "class" => "market_stock"));
                $itemsCount = count($data);
                for ($rowIndex = 1; $rowIndex <= $eachCols; $rowIndex++) {
                    $output .= CHtml::openTag("li", array("style" => "right:0px !important"));
                    $output .= '<table border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;direction:rtl">' . PHP_EOL;
                    $output .= "<tr>" . PHP_EOL;

                    for ($childIndex = 0; $childIndex < $rowLimit && $itemsCount > 0; $childIndex++) {
                        $value = current($data);
                        $output .= '<td class="ms_company" style="direction:ltr;white-space: nowrap;" >' . PHP_EOL;
                        $output .= CHtml::openTag("span", array("class" => "ms_name"));
                        $output .= " " . $value['company_name'] . " ";
                        $output .= CHtml::closeTag("span");

                        $class = "ms_nochange";
                        if ($value['difference_percentage'] < 0) {
                            $class = "ms_dwn";
                            $classPercentage = "ms_dwn_percentage";
                        } elseif (intval($value['difference_percentage'])) {
                            $class = "ms_up";
                            $classPercentage = "ms_up_percentage";
                        }
                        $output .= CHtml::openTag("span", array("class" => $class));
                        $output .= "%" . $value['difference_percentage'] . " ";
                        $output .= CHtml::closeTag("span");
                        $output .= CHtml::openTag("span", array("class" => $classPercentage));
                        $output .= " " . $value['closing_value'] . " ";
                        $output .= CHtml::closeTag("span");
                        $output .= '</td>' . PHP_EOL;
                        next($data);
                        $itemsCount--;
                    }

                    $output .= "</tr>" . PHP_EOL;
                    $output .= "</table>" . PHP_EOL;
                    $output .= CHtml::closeTag("li");
                }
                $output .= CHtml::closeTag("ul");
            }
            echo $output;
        }
        Yii::app()->end();
    }

}
