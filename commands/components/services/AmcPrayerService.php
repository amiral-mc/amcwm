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
class AmcPrayerService extends AmcService {

    public function setInformation() {
        $this->information['table'] = 'prayer_times';
    }

    public function getData() {
        return array();
    }

    protected function update() {
        $currentDate = date("Y-m-d") . " ";
        Yii::app()->db->createCommand('delete from prayer_times')->execute();
        $queryInsert = "INSERT INTO prayer_times (city_id, fajr, sunrise, dhuhr, asr, maghrib, isha) VALUES ";
        $queryData = array();
        $cities = Yii::app()->db->createCommand('select c.code, c.prayer_method as method, cc.city_id , cc.timezone, cc.latitude, cc.longitude
        from countries c 
        join services_cities cc on c.code = cc.country_code')->queryAll();
        $key = Yii::app()->params['muslimsalat'] = "36f27a1d7d7b914a03514660096307f4";
        $time = time();
//          $url = "http://www.islamicfinder.org/prayer_service.php?city={$city_name}&latitude={$city['latitude']}&longitude={$city['longitude']}&timezone={$city['timezone']}&HanfiShafi=1&pmethod=2&simpleFormat=xml";
//            $url = "http://muslimsalat.com/location/date.json?key=36f27a1d7d7b914a03514660096307f4&location={$city['latitude']}&longitude={$city['longitude']}";
//            $xmlData = $this->getXmlFromUrl($url);
        foreach ($cities AS $city) {
            if ($city['method']) {
                $prayTime = new PrayTime($city['method']);
                $times = $prayTime->getPrayerTimes($time, $city['latitude'], $city['longitude'], $city['timezone']);
                if ($times) {
                    $fajr = strtotime("{$currentDate} {$times[0]}");
                    $sunrise = strtotime("{$currentDate} {$times[1]}");
                    $dhuhr = strtotime("{$currentDate} {$times[2]}");
                    $asr = strtotime("{$currentDate} {$times[3]}");
                    $maghrib = strtotime("{$currentDate} {$times[5]}");
                    $isha = strtotime("{$currentDate} {$times[6]}");
                    $queryData[] = sprintf(" (%d, %d, %d, %d, %d, %d, %d) ", $city['city_id'], $fajr, $sunrise, $dhuhr, $asr, $maghrib, $isha);
                }
            }
        }
        if (count($queryData)) {
            $query = $queryInsert . implode(", ", $queryData);
            Yii::app()->db->createCommand($query)->execute();
        }
    }

}

?>
