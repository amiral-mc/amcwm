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
class AmcPrayerService extends AmcService {
    
    public function setInformation() {
        $this->information['table'] = 'prayer_times';
    }
    
    public function getData(){
        return array();
    }
    
    protected function update(){        
        $currentDate = date("Y-m-d") . " ";
        Yii::app()->db->createCommand('delete from prayer_times')->execute();
        $queryInsert = "INSERT INTO prayer_times (city_id, fajr, sunrise, dhuhr, asr, maghrib, isha) VALUES ";
        $queryData   = array();
        $cities = Yii::app()->db->createCommand('select * from services_cities')->queryAll();
        foreach ($cities AS $city){
            $city_name = urlencode($city['city']);
            $url = "http://www.islamicfinder.org/prayer_service.php?city={$city_name}&latitude={$city['latitude']}&longitude={$city['longitude']}&timezone={$city['timezone']}&HanfiShafi=1&pmethod=2&simpleFormat=xml";
            $xmlData = $this->getXmlFromUrl($url);
            if ($xmlData) {
//                $doc = new DOMDocument();
//                @$doc->loadXML($xmlData);
//                $items = $doc->getElementsByTagName("item");
//                $itemCount = $items->length;
                $data = new SimpleXMLElement($xmlData);
                $fajr = strtotime($currentDate . (string) $data->fajr . ":00");
                $sunrise = strtotime($currentDate . (string) $data->sunrise);
                $dhuhrString = (string) $data->dhuhr;
                $dhuhrTime = explode(":", $dhuhrString);
                if ($dhuhrTime[0] > 1) {
                    $dhuhr = strtotime($currentDate . $dhuhrString);
                } else {
                    $dhuhr = strtotime($currentDate . $dhuhrString) + (12 * 60 * 60);
                }
                $asr = strtotime($currentDate . (string) $data->asr) + (12 * 60 * 60);
                $maghrib = strtotime($currentDate . (string) $data->maghrib) + (12 * 60 * 60);
                $isha = strtotime($currentDate . (string) $data->isha) + (12 * 60 * 60);                

                $queryData[] = sprintf(" (%d, %d, %d, %d, %d, %d, %d) ", $city['city_id'], $fajr, $sunrise, $dhuhr, $asr, $maghrib, $isha);
            }
        }
        
        if(count($queryData)){
            $query = $queryInsert . implode(", ", $queryData);
            Yii::app()->db->createCommand($query)->execute();
        }
    }

}

?>
