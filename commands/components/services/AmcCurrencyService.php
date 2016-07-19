<?php
/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @version 1.0
 */
class AmcCurrencyService extends AmcService
{

    public function setInformation()
    {
        $this->information['compare_table'] = 'currency_compare';
        $this->information['rss_url'] = 'http://finance.yahoo.com/webservice/v1/symbols/allcurrencies/quote;currency=true?view=basic&format=json';
    }

    public function getData()
    {
        return array();
    }

    protected function update()
    {
        $data = $this->getXmlFromUrl($this->information['rss_url']);
        $orgData = $this->cleanData(json_decode($data));
        $currencies = Yii::app()->db->createCommand('select * from currency')->queryAll();
        // first remove all compare from currency_compare table
        $query = sprintf("DELETE FROM {$this->information['compare_table']}");
        Yii::app()->db->createCommand($query)->execute();
        $currenciesBase = array();
        $queryHeader = "INSERT INTO {$this->information['compare_table']} (rate, compare_from, compare_to) VALUES ";
        $queryChilds = array();
        $currenciesData = array();
        foreach ($currencies as $currencyRow) {
            $currenciesData[$currencyRow['currency_code']] = $currencyRow['currency_code'];
        }

        foreach ($currenciesData as $currencyBase) {
            $data = $this->convertBase($currencyBase, $orgData);
            if ($data) {
                foreach ($data['rates'] as $currencyFrom => $rate) {
                    if (isset($currenciesData[$currencyFrom])) {
                        $queryChilds[] = sprintf("(%.3f, '%s', '%s')", $rate, $currencyFrom, $currencyBase);
                    }
                }
            }
        }
        if (count($queryChilds)) {
            $query = $queryHeader . implode(",\n", $queryChilds) . ";";
            Yii::app()->db->createCommand($query)->execute();
        }
    }

    /**
     * Parse exchange rates from source response
     * @param array $data
     * @return array
     */
    private function cleanData($data)
    {
        $array = array();
        $array['base'] = 'USD';
        foreach ($data->list->resources as $r) {
            foreach ($r->resource as $key => $val) {
                // only scrape currencies
                if ($key === 'fields') {
                    if (stripos($val->name, '/') !== false) {
                        $array['rates'][(string) substr($val->name, -3)] = (float) $val->price;
                    }
                }
            }
        }
        $array['rates']['USD'] = (float) 1.00;
        // sort alphabetically
        ksort($array['rates']);
        return $array;
    }

    /**
     * 1) Download exchange rates from Yahoo
     * 2) Parse rates
     * 3) Change/Convert base currency (currency must be valid)
     * @param  $string base
     */
    public function rates($base)
    {
        $base = strtoupper($base);

        // confirm rates were fetched correctly
        if ($this->fetch()) {
            // clean source & convert base currency
            $this->cleanSource();
            $data = $this->convertBase();
            if ($data) {
                // return rates
                return $data;
            }
            exit('Base currency does not exist in provided source');
        }
        exit('Error fetching source');
    }

    /**
     * Convert rates to use base currency
     *
     * @return array converted data
     */
    private function convertBase($base, $data)
    {

        // check that defined base currency exists
        if (!isset($data['rates'][$base])) {
            return array();
        }

        // convert currencies
        if ($base !== $data['base']) {
            $rates = array();
            $base_rate = $data['rates'][$base];
            foreach ($data['rates'] as $key => $val) {
                if ($key !== $base) {
                    // round to 6 decimal places
                    $rates[$key] = (float) round($val * (1 / $base_rate), 6);
                } else {
                    $rates['USD'] = (float) round(1 / $base_rate, 6);
                    $rates[$base] = (float) 1.00;
                }
            }
            $data['base'] = $base;
            $data['rates'] = $rates;
        }
        return $data;
    }
}
