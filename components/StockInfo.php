<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of StockInfo
 * @author Amiral Management Corporation amc.amiral.com
 */
class StockInfo {

    public function __construct() {
        
    }

    function generate() {
        $output = "";
        $uaeStockUrl = "http://www.dfm.ae/ws/TickerData.asmx/GetTickerData";
        $xmlData = $this->getXmlFromUrl($uaeStockUrl);
        if ($xmlData) {
            $data = new SimpleXMLElement($xmlData);
            if (count($data)) {
                $output .= CHtml::openTag("ul", array("id" => "stockSlider", "class" => "market_stock"));
                $row = 0;
                $eachCols = 4;
                $itemsCount = count($data);
                for ($rowIndex = 1; $rowIndex < $itemsCount; $rowIndex = $rowIndex + $eachCols) {
                    $output .= CHtml::openTag("li", array("style" => "right:0px !important"));
                    $output .= '<table border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;direction:rtl">' . PHP_EOL;
                    $output .= "<tr>" . PHP_EOL;

                    for ($childIndex = $rowIndex; $childIndex < $rowIndex + $eachCols; $childIndex++) {

                        $stockData = explode("|", $data[0]->anyType[$childIndex]);
                        $companyID = (isset($stockData[0])) ? $stockData[0] : null;
                        $value = (isset($stockData[1])) ? $stockData[1] : null;
                        $change = (isset($stockData[2])) ? $stockData[2] . "%  " : null;
                        $type = (isset($stockData[3])) ? $stockData[3] : null;

                        $output .= '<td class="ms_company" style="direction:ltr;white-space: nowrap;" >' . PHP_EOL;
                        if ($companyID) {
                            $output .= CHtml::openTag("span", array("class" => "ms_name"));
                            $output .= " " . $companyID . " ";
                            $output .= CHtml::closeTag("span");
                            $output .= CHtml::openTag("span", array("class" => "ms_point"));
                            $output .= " " . $value . " ";
                            $output .= CHtml::closeTag("span");
                            $class = "ms_nochange";
                            if ($change < 0) {
                                $class = "ms_dwn";
                            } elseif (intval($change)) {
                                $class = "ms_up";
                            }
                            $output .= CHtml::openTag("span", array("class" => $class));
                            $output .= $change;
                            $output .= CHtml::closeTag("span");
                        } else {
                            $output .= "&nbsp;";
                        }
                        $output .= '</td>' . PHP_EOL;
                    }

                    $output .= "</tr>" . PHP_EOL;
                    $output .= "</table>" . PHP_EOL;
                    $output .= CHtml::closeTag("li");
                }
                $output .= CHtml::closeTag("ul");
            }
        }
        echo $output;
    }

    /**
     * Get xml from the given $url.
     * @param string $url
     * @param array $postParams
     * @access public
     * @static
     * @return string
     */
    public static function getXmlFromUrl($url, $postParams = array()) {
        $content = "";
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if (count(Yii::app()->params['proxy'])) {
                curl_setopt($ch, CURLOPT_PROXY, Yii::app()->params['proxy']['host']);
                curl_setopt($ch, CURLOPT_PROXYPORT, Yii::app()->params['proxy']['port']);
            }
            if (count($postParams)) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
            }
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
            $content = curl_exec($ch);
            curl_close($ch);
        } else {
            $content = file_get_contents($url);
        }
        return $content;
    }

}

?>
