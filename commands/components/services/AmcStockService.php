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
class AmcStockService extends AmcService {

    public function setInformation() {
        $this->information['table'] = 'weather_cities';
    }

    public function getData() {
        return array();
    }

    protected function update() {
        /*
         * Read the following article
          http://stackoverflow.com/questions/281263/where-can-i-get-free-real-time-stock-data
         */

        $url = "http://www.xbs-me.com/HomePage.aspx?Anthem_CallBack=true";
        //$url = "http://localhost/stock.php?Anthem_CallBack=true";
        $xmlData = $this->getXmlFromUrl($url, array('FeedControl1$Rbl' => 1));
        print_r($xmlData);
    }

}

?>
