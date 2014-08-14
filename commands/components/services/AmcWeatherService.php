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
        $queryUpdate = "";
        $cities = Yii::app()->db->createCommand('select * from weather_cities')->queryAll();
        foreach ($cities AS $city) {
            $url = "http://wxdata.weather.com/wxdata/weather/local/{$city['weather_city']}?cc=*&dayf={$forecastingDays}&link=xoap&prod=xoap&par={$weatherConf['par']}&key={$weatherConf['key']}";
            $xmlData = $this->getXmlFromUrl($url);

            $doc = new DOMDocument();
            @$doc->loadXML($xmlData);
            $items = $doc->getElementsByTagName("weather");
            if ($items->length) {
                $data = $items->item(0);
                $head = $data->getElementsByTagName('head')->item(0);
                $loc = $data->getElementsByTagName('loc')->item(0);
                if ($head) {
                    foreach ($head->childNodes as $headNode) {
                        if ($headNode->nodeType == XML_ELEMENT_NODE) {
                            switch ($headNode->nodeName) {
                                case 'ut';
                                    $temperature = $headNode->nodeValue;
                                    break;
                            }
                        }
                    }
                }
                $sunr = null;
                $suns = null;
                $humidity = null;
                $visibility = null;
                $temp = null;
                $icon = null;
                $status = null;
                $feelslik = null;
                $wind = array();
                $pressure = array();
                $moon = array();
                $forecast = array();
                $cc = $data->getElementsByTagName('cc')->item(0);
                $loc = $data->getElementsByTagName('loc')->item(0);
                $dayf = $data->getElementsByTagName('dayf')->item(0);
                if ($dayf) {
                    $days = $dayf->parentNode->getElementsByTagName('day');
                    if ($days) {
                        foreach ($days as $dayNode) {
                            if ($dayNode->nodeType == XML_ELEMENT_NODE) {
                                $daysData = array();
                                $daysData["dt"] = $dayNode->getAttribute('dt');
                                $daysData["t"] = $dayNode->getAttribute('t');
                                $dayIndex = $dayNode->getAttribute('d');
                                if ($dayNode->hasChildNodes()) {
                                    foreach ($dayNode->childNodes as $dayItemNode) {
                                        if ($dayItemNode->nodeType == XML_ELEMENT_NODE) {
                                            switch ($dayItemNode->nodeName) {
                                                case 'hi':
                                                    $daysData["hi"] = round(((int) $dayItemNode->nodeValue - 32) * 5 / 9);
                                                    break;
                                                case 'low':
                                                    $daysData["low"] = round(((int) $dayItemNode->nodeValue - 32) * 5 / 9);
                                                    break;
                                                case 'sunr':
                                                    $daysData["sunr"] = $dayItemNode->nodeValue;
                                                    break;
                                                case 'suns':
                                                    $daysData["suns"] = $dayItemNode->nodeValue;
                                                    break;
                                                case 'part':
                                                    $partIndex = $dayItemNode->getAttribute('p');
                                                    $daysData["part"][$partIndex] = array();
                                                    if ($dayItemNode->hasChildNodes()) {
                                                        foreach ($dayItemNode->childNodes as $partNode) {
                                                            if ($partNode->nodeType == XML_ELEMENT_NODE) {
                                                                switch ($partNode->nodeName) {
                                                                    case 'icon':
                                                                        $daysData["part"][$partIndex]["icon"] = $partNode->nodeValue;
                                                                        break;
                                                                    case 't':
                                                                        $daysData["part"][$partIndex]["t"] = $partNode->nodeValue;
                                                                        break;
                                                                    case 'bt':
                                                                        $daysData["part"][$partIndex]["bt"] = $partNode->nodeValue;
                                                                        break;
                                                                    case 'ppcp':
                                                                        $daysData["part"][$partIndex]["ppcp"] = $partNode->nodeValue;
                                                                        break;
                                                                    case 'hmid':
                                                                        $daysData["part"][$partIndex]["hmid"] = $partNode->nodeValue;
                                                                        break;
                                                                    case 'wind':
                                                                        $daysData["part"][$partIndex]["wind"] = array();
                                                                        if ($partNode->hasChildNodes()) {
                                                                            foreach ($partNode->childNodes as $windNode) {
                                                                                if ($windNode->nodeType == XML_ELEMENT_NODE) {
                                                                                    switch ($windNode->nodeName) {
                                                                                        case 's':
                                                                                            $daysData["part"][$partIndex]["wind"]["s"] = $windNode->nodeValue;
                                                                                            break;
                                                                                        case 'gust':
                                                                                            $daysData["part"][$partIndex]["wind"]["gust"] = $windNode->nodeValue;
                                                                                            break;
                                                                                        case 'd':
                                                                                            $daysData["part"][$partIndex]["wind"]["d"] = $windNode->nodeValue;
                                                                                            break;
                                                                                        case 't':
                                                                                            $daysData["part"][$partIndex]["wind"]["t"] = $windNode->nodeValue;
                                                                                            break;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    break;
                                            }
                                        }
                                    }
                                }
                                $forecast[$dayIndex] = $daysData;
                            }
                        }
                    }
                }
                if ($loc) {
                    if ($loc->hasChildNodes()) {
                        foreach ($loc->childNodes as $locNode) {
                            if ($locNode->nodeType == XML_ELEMENT_NODE) {
                                switch ($locNode->nodeName) {
                                    case 'sunr':
                                        $sunr = $locNode->nodeValue;
                                        break;
                                    case 'suns':
                                        $suns = $locNode->nodeValue;
                                        break;
                                }
                            }
                        }
                    }
                }
                if ($cc) {
                    if ($cc->hasChildNodes()) {
                        foreach ($cc->childNodes as $ccNode) {
                            if ($ccNode->nodeType == XML_ELEMENT_NODE) {
                                switch ($ccNode->nodeName) {
                                    case 'hmid':
                                        $ccNode->nodeValue;
                                        $humidity = $ccNode->nodeValue;
                                        break;
                                    case 'vis':
                                        $ccNode->nodeValue;
                                        $visibility = $ccNode->nodeValue;
                                        break;
                                    case 'tmp':
                                        $ccNode->nodeValue;
                                        $temp = round(((int) $ccNode->nodeValue - 32) * 5 / 9);
                                        break;

                                    case 'icon':
                                        $icon = $ccNode->nodeValue;
                                        break;
                                    case 't':
                                        $status = $ccNode->nodeValue;
                                        break;
                                    case 'flik':
                                        $feelslik = round(((int) $ccNode->nodeValue - 32) * 5 / 9);
                                        break;
                                    case 'wind':
                                        foreach ($ccNode->childNodes as $windNode) {
                                            if ($windNode->nodeType == XML_ELEMENT_NODE) {
                                                switch ($windNode->nodeName) {
                                                    case 's':
                                                        $wind['speed'] = $windNode->nodeValue;
                                                        break;
                                                    case 'gust':
                                                        $wind['gust'] = $windNode->nodeValue;
                                                        break;
                                                    case 'd':
                                                        $wind['d'] = $windNode->nodeValue;
                                                        break;
                                                    case 't':
                                                        $wind['from'] = $windNode->nodeValue;
                                                        break;
                                                }
                                            }
                                        }
                                        break;
                                    case 'bar':
                                        foreach ($ccNode->childNodes as $barNode) {
                                            if ($barNode->nodeType == XML_ELEMENT_NODE) {
                                                switch ($barNode->nodeName) {
                                                    case 'r':
                                                        $pressure['r'] = $barNode->nodeValue;
                                                        break;
                                                    case 'd':
                                                        $pressure['d'] = $barNode->nodeValue;
                                                        break;
                                                }
                                            }
                                        }
                                        break;
                                    case 'uv':
                                        $uvIndex = array();
                                        foreach ($ccNode->childNodes as $uvNode) {
                                            if ($uvNode->nodeType == XML_ELEMENT_NODE) {
                                                switch ($uvNode->nodeName) {
                                                    case 'i':
                                                        $uvIndex['i'] = $uvNode->nodeValue;
                                                        break;
                                                    case 't':
                                                        $uvIndex['t'] = $uvNode->nodeValue;
                                                        break;
                                                }
                                            }
                                        }
                                        break;
                                    case 'moon':
                                        foreach ($ccNode->childNodes as $moonNode) {
                                            if ($moonNode->nodeType == XML_ELEMENT_NODE) {
                                                switch ($moonNode->nodeName) {
                                                    case 'icon':
                                                        $moon['icon'] = $moonNode->nodeValue;
                                                        break;
                                                    case 't':
                                                        $moon['t'] = $moonNode->nodeValue;
                                                        break;
                                                }
                                            }
                                        }
                                        break;
                                }
                            }
                        }
                    }
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
                        , Yii::app()->db->quoteValue(CJSON::encode($wind))
                        , Yii::app()->db->quoteValue(CJSON::encode($pressure))
                        , $humidity
                        , $visibility
                        , Yii::app()->db->quoteValue(CJSON::encode($uvIndex))
                        , Yii::app()->db->quoteValue(CJSON::encode($moon))
                        , Yii::app()->db->quoteValue(CJSON::encode($forecast))
                        , Yii::app()->db->quoteValue($city['city_id']));               
                //die($queryUpdate);
                Yii::app()->db->createCommand($queryUpdate)->execute();
                
            } else {
                echo "City {$city['weather_city']} is invalid ... \n";
            }          
        }
    }

}

?>
