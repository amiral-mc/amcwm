<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
class AmcCurrencyService extends AmcService {

    public function setInformation() {
        $this->information['compare_table'] = 'currency_compare';
        $this->information['rss_url'] = 'http://themoneyconverter.com';
    }

    public function getData() {
        return array();
    }

    protected function update() {
        $currencies = Yii::app()->db->createCommand('select * from currency')->queryAll();
        // first remove all compare from currency_compare table
        $query = sprintf("DELETE FROM {$this->information['compare_table']}");
        Yii::app()->db->createCommand($query)->execute();
        $currenciesFrom = array();
        $queryHeader = "INSERT INTO {$this->information['compare_table']} (rate, compare_from, compare_to) VALUES ";
        $queryChilds = array();
        foreach ($currencies as $currencyRow) {
            $currenciesFrom[$currencyRow['currency_code']] = $currencyRow['currency_code'];
        }
        foreach ($currenciesFrom as $currencyFrom) {
            $currencyCode = $currencyFrom;
            $url = "{$this->information['rss_url']}/rss-feed/{$currencyCode}/rss.xml";
            $xmlData = $this->getXmlFromUrl($url);
            if ($xmlData) {
                $doc = new DOMDocument();
                @$doc->loadXML($xmlData);
                $items = $doc->getElementsByTagName("item");
                $itemCount = $items->length;
//                $data = new SimpleXMLElement($xmlData);
                if ($itemCount) {
                    for ($i = 0; $i < $itemCount; $i++) {
                        $node = $items->item($i);
                        $title = (string) str_replace(array("\n", "\n\r", "\r"), '', $node->getElementsByTagName('title')->item(0)->nodeValue);
                        $description = (string) $node->getElementsByTagName('description')->item(0)->nodeValue;
//                    foreach ($data->channel->item as $item) {
                        $tmpCurrencyName = explode("/", (string) $title);
                        $currencyName = $tmpCurrencyName[0];
                        $tmpCurrency = explode("=", $description);
                        $currency = (float) $tmpCurrency[1];
                        $rate = (1 / $currency);
                        if (array_key_exists($currencyName, $currenciesFrom)) {
                            $queryChilds[] = sprintf("(%.2f, '%s', '%s')", $rate, $currencyCode, $currencyName);
                        }
                    }//end foreach channel data
                }
            }// end if xml data
        }

        if (count($queryChilds)) {
            $query = $queryHeader . implode(",\n", $queryChilds) . ";";
            Yii::app()->db->createCommand($query)->execute();
        }
    }

}

?>
