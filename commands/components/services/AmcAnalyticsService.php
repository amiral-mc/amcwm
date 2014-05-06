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
class AmcAnalyticsService extends AmcService {

    public function setInformation() {
        $this->information['table'] = 'weather_cities';
    }

    public function getData() {
        return array();
    }

    protected function update() {
        $currentDate = date("Y-m-d");
    }

}

?>
