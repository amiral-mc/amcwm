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

/**
 * To run this service:
 * /var/www/{projectName}/protected/amcc services --service=weather
 * Where {projectName} is the project name
 */
class AmcWeatherService extends AmcService {

    public function setInformation() {
        $this->information['table'] = 'weather_cities';
    }

    public function getData() {
        return array();
    }

    protected function update() {
//        $currentDate = date("Y-m-d") . " ";
        $forecastingDays = 5;
        $weatherConf = Yii::app()->params["weather"];
        $ok = false;
        $queryUpdate = "";
        $cities = Yii::app()->db->createCommand('select * from weather_cities')->queryAll();
        foreach ($cities AS $city) {
            $url = "http://xoap.weather.com/weather/local/{$city['weather_city']}?cc=*&dayf={$forecastingDays}&link=xoap&prod=xoap&par={$weatherConf['par']}&key={$weatherConf['key']}";
            $xmlData = $this->getXmlFromUrl($url);
            if ($xmlData) {
                $ok = true;
                $data = new SimpleXMLElement($xmlData);
                $temp = round(((int) $data->cc->tmp - 32) * 5 / 9);
                $icon = (string) $data->cc->icon;
                
                $temperature = (string) $data->head->ut;
                $sunr = (string) $data->loc->sunr;
                $suns = (string) $data->loc->suns;
                $status = (string) $data->cc->t;
                $feelslik = round(((int) $data->cc->flik - 32) * 5 / 9);
                $wind = CJSON::encode(array("speed" => (string) $data->cc->wind->s, "gust" => (string) $data->cc->wind->gust, "d" => (string) $data->cc->wind->d, "from" => (string) $data->cc->wind->t));
                $pressure = CJSON::encode(array("r" => (string) $data->cc->bar->r, "d" => (string) $data->cc->bar->d));
                $humidity = (string) $data->cc->hmid;
                $visibility = (string) $data->cc->vis;
                $uv_index = CJSON::encode(array("i" => (string) $data->cc->uv->i, "t" => (string) $data->cc->uv->t));
                $moon = CJSON::encode(array("icon" => (string) $data->cc->moon->icon, "t" => (string) $data->cc->moon->t));
                
                // forecasting
                $forecast = array();
                if (count($data->dayf->day)) {
                    foreach ($data->dayf->day as $days) {
                        $daysData = array();
                        $daysData["dt"] = (string) $days['dt'];
                        $daysData["t"] = (string) $days['t'];
                        $daysData["hi"] = round(((int) $days->hi - 32) * 5 / 9);
                        $daysData["low"] = round(((int) $days->low - 32) * 5 / 9);
                        $daysData["sunr"] = (string) $days->sunr;
                        $daysData["suns"] = (string) $days->suns;
                        foreach ($days->part as $dayParts) {
                            $daysData["part"][(string) $dayParts['p']] = array(
                                'icon' => (string) $dayParts->icon,
                                't' => (string) $dayParts->t,
                                'wind' => array(
                                    's' => (string) $dayParts->wind->s,
                                    'gust' => (string) $dayParts->wind->gust,
                                    'd' => (string) $dayParts->wind->d,
                                    't' => (string) $dayParts->wind->t,
                                ),
                                'bt' => (string) $dayParts->bt,
                                'ppcp' => (string) $dayParts->ppcp,
                                'hmid' => (string) $dayParts->hmid,
                            );
                        }
                        $forecast[(string) $days['d']] = $daysData;
                    } // end foreach
                }

                /*
                 */
                $queryUpdate = sprintf("update weather_cities set 
                                icon = %d 
                                , temp = %d 
                                , status = %s 
                                , temperature = %s 
                                , sunr = %s 
                                , suns = %s 
                                , feelslik = %d 
                                , wind = %s 
                                , pressure = %s 
                                , humidity = %d 
                                , visibility = %d 
                                , uv_index = %s 
                                , moon = %s 
                                , forecast = %s 
                                where city_id = %s; \n", $icon
                        , $temp
                        , Yii::app()->db->quoteValue($status)
                        , Yii::app()->db->quoteValue($temperature)
                        , Yii::app()->db->quoteValue($sunr)
                        , Yii::app()->db->quoteValue($suns)
                        , $feelslik
                        , Yii::app()->db->quoteValue($wind)
                        , Yii::app()->db->quoteValue($pressure)
                        , $humidity
                        , $visibility
                        , Yii::app()->db->quoteValue($uv_index)
                        , Yii::app()->db->quoteValue($moon)
                        , Yii::app()->db->quoteValue(CJSON::encode($forecast))
                        , Yii::app()->db->quoteValue($city['city_id']));
            }
            if ($ok) {
                Yii::app()->db->createCommand($queryUpdate)->execute();
            }
        }
    }

}

?>
